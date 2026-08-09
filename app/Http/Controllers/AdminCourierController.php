<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use App\Services\Courier\CourierService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;
use Exception;

class AdminCourierController extends Controller
{
    protected CourierService $courierService;

    public function __construct(CourierService $courierService)
    {
        $this->courierService = $courierService;
    }

    /**
     * Self-healing check: automatically ensure all required courier columns exist in the database table.
     */
    protected function ensureCourierColumnsExist(): void
    {
        try {
            if (Schema::hasTable('orders') && !Schema::hasColumn('orders', 'courier_tracking_id')) {
                Schema::table('orders', function (Blueprint $table) {
                    if (!Schema::hasColumn('orders', 'courier_name')) {
                        $table->string('courier_name')->nullable()->after('status');
                    }
                    if (!Schema::hasColumn('orders', 'courier_tracking_id')) {
                        $table->string('courier_tracking_id')->nullable()->index()->after('courier_name');
                    }
                    if (!Schema::hasColumn('orders', 'courier_consignment_id')) {
                        $table->string('courier_consignment_id')->nullable()->index()->after('courier_tracking_id');
                    }
                    if (!Schema::hasColumn('orders', 'courier_status')) {
                        $table->string('courier_status')->default('pending')->after('courier_consignment_id');
                    }
                    if (!Schema::hasColumn('orders', 'courier_response')) {
                        $table->json('courier_response')->nullable()->after('courier_status');
                    }
                    if (!Schema::hasColumn('orders', 'dispatched_at')) {
                        $table->timestamp('dispatched_at')->nullable()->after('courier_response');
                    }
                });
            }
        } catch (\Throwable $e) {
            Log::warning('Self-healing schema migration for orders table: ' . $e->getMessage());
        }
    }

    /**
     * Display the Courier Hub Management Console.
     */
    public function index(Request $request)
    {
        $this->ensureCourierColumnsExist();

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

        return \Inertia\Inertia::render('Admin/Courier', [
            'courierSettings' => $siteSettings,
        ]);
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
        $this->ensureCourierColumnsExist();

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
        $this->ensureCourierColumnsExist();

        $order = Order::findOrFail($id);
        $result = $this->courierService->trackOrder($order);

        return response()->json($result);
    }

    /**
     * Run database migrations directly from browser (for cPanel shared hosting).
     */
    public function runMigrations(Request $request)
    {
        try {
            $this->ensureCourierColumnsExist();
            Artisan::call('migrate', ['--force' => true]);
            $output = Artisan::output();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Database migrations executed successfully.',
                    'output' => $output,
                ]);
            }

            return redirect()->back()->with('success', 'Database migrations executed successfully: ' . $output);
        } catch (\Throwable $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Migration error: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->back()->with('error', 'Migration error: ' . $e->getMessage());
        }
    }
}
