<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Public store (Place an order).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:50',
            'shipping_address' => 'required|string',
            'city' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'payment_method' => 'required|string|in:cod,card,bkash,sslcommerz,eps',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.size' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $orderNumber = 'REX-' . strtoupper(Str::random(8));
        $totalAmount = 0;
        $orderItemsData = [];

        foreach ($validated['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            $unitPrice = $product->price;
            $lineTotal = $unitPrice * $item['quantity'];
            $totalAmount += $lineTotal;

            $orderItemsData[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'size' => $item['size'] ?? ($product->sizes[0] ?? '100ml'),
                'unit_price' => $unitPrice,
                'quantity' => $item['quantity'],
                'total_price' => $lineTotal,
            ];

            // Decrement stock if available
            if ($product->stock >= $item['quantity']) {
                $product->decrement('stock', $item['quantity']);
            }
        }

        $order = Order::create([
            'order_number' => $orderNumber,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'shipping_address' => $validated['shipping_address'],
            'city' => $validated['city'],
            'postal_code' => $validated['postal_code'] ?? null,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => $validated['payment_method'],
        ]);

        foreach ($orderItemsData as $itemData) {
            $order->items()->create($itemData);
        }

        return response()->json([
            'message' => 'Order placed successfully',
            'order' => $order->load('items'),
        ], 201);
    }

    /**
     * Admin index (List orders).
     */
    public function index(Request $request)
    {
        $query = Order::with('items');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->orderBy('created_at', 'desc')->paginate(20));
    }

    /**
     * Admin show order detail.
     */
    public function show(Order $order)
    {
        return response()->json($order->load('items.product'));
    }

    /**
     * Admin update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'payment_status' => 'nullable|string',
        ]);

        $order->update($validated);

        return response()->json($order);
    }
}
