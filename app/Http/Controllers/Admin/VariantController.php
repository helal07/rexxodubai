<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Variant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VariantController extends Controller
{
    public function index()
    {
        $variants = Variant::orderBy('name')->get();

        return Inertia::render('Admin/Variants/Index', [
            'variants' => $variants,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:variants',
        ]);

        Variant::create($validated);

        return back()->with('success', 'Variant created successfully.');
    }

    public function update(Request $request, $id)
    {
        $variant = Variant::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:variants,name,'.$variant->id,
        ]);

        $variant->update($validated);

        return back()->with('success', 'Variant updated successfully.');
    }

    public function destroy($id)
    {
        $variant = Variant::findOrFail($id);
        $variant->delete();

        return back()->with('success', 'Variant deleted successfully.');
    }
}
