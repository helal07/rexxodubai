<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'reference_no' => 'required|string|unique:purchases',
            'purchase_date' => 'required|date',
            'status' => 'required|string',
            'payment_status' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            foreach ($validated['items'] as $item) {
                $totalAmount += $item['quantity'] * $item['unit_cost'];
            }

            $purchase = Purchase::create([
                'supplier_id' => $validated['supplier_id'],
                'reference_no' => $validated['reference_no'],
                'purchase_date' => $validated['purchase_date'],
                'status' => $validated['status'],
                'total_amount' => $totalAmount,
                'payment_status' => $validated['payment_status'],
            ]);

            foreach ($validated['items'] as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                ]);

                if ($validated['status'] === 'Received') {
                    $product = Product::find($item['product_id']);
                    $product->stock += $item['quantity'];
                    $product->save();
                }
            }

            DB::commit();

            return response()->json(['message' => 'Purchase created successfully.', 'purchase' => $purchase]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Failed to create purchase.', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->delete();

        return response()->json(['message' => 'Purchase deleted successfully.']);
    }
}
