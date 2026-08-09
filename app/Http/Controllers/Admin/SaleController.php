<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string',
            'customer_phone' => 'nullable|string',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string',
            'courier_name' => 'nullable|string',
            'shipping_cost' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $orderItemsData = [];

            foreach ($validated['items'] as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                
                if ($product->stock < $itemData['quantity']) {
                    throw new \Exception("Insufficient stock for product: " . $product->name);
                }

                $totalPrice = $product->price * $itemData['quantity'];
                $subtotal += $totalPrice;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'size' => $product->volume . 'ml',
                    'unit_price' => $product->price,
                    'quantity' => $itemData['quantity'],
                    'total_price' => $totalPrice,
                ];

                // Deduct stock
                $product->stock -= $itemData['quantity'];
                $product->save();
            }

            $shippingCost = $validated['shipping_cost'] ?? 0;
            $discount = $validated['discount'] ?? 0;
            $grandTotal = $subtotal + $shippingCost - $discount;

            $order = Order::create([
                'order_number' => 'RX-' . strtoupper(Str::random(6)),
                'customer_name' => $validated['customer_name'],
                'customer_email' => 'pos@store.local', // Placeholder
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'total_amount' => $grandTotal,
                'status' => 'completed',
                'payment_status' => 'paid',
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'discount_amount' => $discount,
            ]);

            foreach ($orderItemsData as $item) {
                $item['order_id'] = $order->id;
                OrderItem::create($item);
            }

            DB::commit();
            return response()->json(['message' => 'Sale created successfully!', 'order' => $order]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create sale.', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        
        // Restore stock
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->stock += $item->quantity;
                $item->product->save();
            }
        }

        $order->delete();
        return response()->json(['message' => 'Sale deleted and stock restored.']);
    }
}
