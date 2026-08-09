<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:customers',
            'address' => 'required|string|max:255',
        ]);

        Customer::create($validated);

        if ($request->header('X-Inertia')) {
            return redirect()->back()->with('success', 'Customer created successfully.');
        }

        return response()->json(['message' => 'Customer created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:customers,phone,' . $id,
            'address' => 'nullable|string|max:255',
        ]);

        $customer->update($validated);

        if ($request->header('X-Inertia')) {
            return redirect()->back()->with('success', 'Customer updated successfully.');
        }

        return response()->json(['message' => 'Customer updated successfully.']);
    }

    public function destroy(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        if ($request->header('X-Inertia')) {
            return redirect()->back()->with('success', 'Customer deleted successfully.');
        }

        return response()->json(['message' => 'Customer deleted successfully.']);
    }
}
