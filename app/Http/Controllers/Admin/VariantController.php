<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    public function index()
    {
        $variants = \App\Models\Variant::orderBy('name')->get();
        return \Inertia\Inertia::render('Admin/Variants/Index', [
            'variants' => $variants
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:variants',
        ]);

        \App\Models\Variant::create($validated);
        return back()->with('success', 'Variant created successfully.');
    }

    public function update(Request $request, $id)
    {
        $variant = \App\Models\Variant::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:variants,name,' . $variant->id,
        ]);

        $variant->update($validated);
        return back()->with('success', 'Variant updated successfully.');
    }

    public function destroy($id)
    {
        $variant = \App\Models\Variant::findOrFail($id);
        $variant->delete();
        return back()->with('success', 'Variant deleted successfully.');
    }
}
