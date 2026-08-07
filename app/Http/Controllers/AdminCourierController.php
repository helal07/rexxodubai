<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use App\Services\Courier\CourierService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class AdminCourierController extends Controller
{
    protected CourierService $courierService;

    public function __construct(CourierService $courierService)
    {
        $this->courierService = $courierService;
    }

    /**
     * Display the Courier Hub Management Console.
     */
    public function index(Request $request)
    {
        $couriers = $this->courierService->getCouriers();

        // Orders needing dispatch
        $pendingOrders = Order::with('items')
            ->where(function ($q) {
                $q->whereNull('courier_tracking_id')
                  ->orWhere('courier_tracking_id', '');
            })
            ->whereIn('status', ['pending', 'processing'])
            ->latest()
            ->get();

        // Dispatched orders history
        $historyQuery = Order::with('items')
            ->where(function ($q) {
                $q->whereNotNull('courier_tracking_id')
                  ->where('courier_tracking_id', '!=', '');
            })
            ->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $historyQuery->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('courier_name', 'like', "%{$search}%")
                  ->orWhere('courier_tracking_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('courier') && $request->input('courier') !== 'all') {
            $historyQuery->where('courier_name', $request->input('courier'));
        }

        $historyOrders = $historyQuery->paginate(15)->withQueryString();

        // Metrics
        $totalCouriers = count($couriers);
        $activeCouriers = count(array_filter($couriers, fn($c) => ($c['status'] ?? '') === 'active'));
        $pendingDispatchCount = $pendingOrders->count();
        $dispatchedTodayCount = Order::whereDate('dispatched_at', today())->count();
        $totalDispatchedCount = Order::whereNotNull('dispatched_at')->count();

        $siteSettings = Setting::pluck('value', 'key')->all();

        return view('admin.courier', compact(
            'couriers',
            'pendingOrders',
            'historyOrders',
            'totalCouriers',
            'activeCouriers',
            'pendingDispatchCount',
            'dispatchedTodayCount',
            'totalDispatchedCount',
            'siteSettings'
        ));
    }

    /**
     * Save all courier configurations to database.
     */
    public function saveSettings(Request $request)
    {
        $couriersData = $request->input('couriers', []);

        if (is_string($couriersData)) {
            $couriersData = json_decode($couriersData, true);
        }

        if (!is_array($couriersData)) {
            return response()->json(['success' => false, 'message' => 'Invalid courier configuration format.'], 422);
        }

        $this->courierService->saveCouriers($couriersData);

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Courier configurations successfully saved to database.',
                'couriers' => $this->courierService->getCouriers(),
            ]);
        }

        return redirect()->back()->with('success', 'Courier configurations saved successfully.');
    }

    /**
     * Save a single courier partner's configuration.
     */
    public function saveSingleCourier(Request $request, $key)
    {
        $validated = $request->validate([
            'name' => 'nullable|string',
            'phone' => 'nullable|string',
            'zone' => 'nullable|string',
            'rate' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive',
            'mode' => 'nullable|string|in:live,sandbox,manual',
            'track_url_template' => 'nullable|string',
            'credentials' => 'nullable|array',
        ]);

        $updated = $this->courierService->saveCourierConfig($key, $validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Courier [{$key}] configuration updated.",
                'courier' => $updated,
            ]);
        }

        return redirect()->back()->with('success', "Configuration for {$key} updated.");
    }

    /**
     * Test live API connection for a given courier.
     */
    public function testConnection(Request $request)
    {
        $provider = $request->input('provider');
        $credentials = $request->input('credentials', []);
        $mode = $request->input('mode', 'live');

        if (!$provider) {
            return response()->json(['success' => false, 'message' => 'Courier provider is required.'], 422);
        }

        // If credentials not passed directly in request, fetch from saved settings
        if (empty($credentials)) {
            $allCouriers = $this->courierService->getCouriers();
            $credentials = $allCouriers[$provider]['credentials'] ?? [];
            $mode = $allCouriers[$provider]['mode'] ?? $mode;
        }

        $result = $this->courierService->testConnection($provider, $credentials, $mode);

        return response()->json($result);
    }

    /**
     * Dispatch an order to the selected courier.
     */
    public function dispatchOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'provider' => 'required|string',
            'note' => 'nullable|string',
            'tracking_id' => 'nullable|string',
        ]);

        $order = Order::findOrFail($request->input('order_id'));
        $provider = $request->input('provider');

        try {
            $result = $this->courierService->dispatchOrder(
                $order,
                $provider,
                $request->only(['note', 'tracking_id', 'store_id'])
            );

            if ($request->expectsJson()) {
                return response()->json($result);
            }

            return redirect()->back()->with('success', $result['message']);
        } catch (Exception $e) {
            Log::error("Courier Dispatch Failed for Order #{$order->order_number}: " . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dispatch failed: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->back()->with('error', 'Dispatch failed: ' . $e->getMessage());
        }
    }

    /**
     * Live Track an Order.
     */
    public function trackOrder($id)
    {
        $order = Order::findOrFail($id);
        $result = $this->courierService->trackOrder($order);

        return response()->json($result);
    }
}
