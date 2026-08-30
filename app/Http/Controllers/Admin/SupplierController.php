<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20|unique:suppliers',
            'city_country' => 'nullable|string|max:255',
        ]);

        Supplier::create($validated);

        if ($request->header('X-Inertia')) {
            return redirect()->back()->with('success', 'Supplier created successfully.');
        }

        return response()->json(['message' => 'Supplier created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20|unique:suppliers,phone,'.$id,
            'city_country' => 'nullable|string|max:255',
        ]);

        $supplier->update($validated);

        if ($request->header('X-Inertia')) {
            return redirect()->back()->with('success', 'Supplier updated successfully.');
        }

        return response()->json(['message' => 'Supplier updated successfully.']);
    }

    public function destroy(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        if ($request->header('X-Inertia')) {
            return redirect()->back()->with('success', 'Supplier deleted successfully.');
        }

        return response()->json(['message' => 'Supplier deleted successfully.']);
    }
}
