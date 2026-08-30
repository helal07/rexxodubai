<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MenuItemController extends Controller
{
    /**
     * Public endpoint returning top-level items with active children tree.
     */
    public function publicTree()
    {
        return Cache::remember('public_menu_tree', 10, function () {
            return MenuItem::whereNull('parent_id')
                ->where('is_active', true)
                ->with(['children' => function ($query) {
                    $query->where('is_active', true)->orderBy('sort_order', 'asc');
                }])
                ->orderBy('sort_order', 'asc')
                ->get();
        });
    }

    /**
     * Admin endpoint: list all menu items with hierarchy.
     */
    public function index()
    {
        return response()->json(
            MenuItem::with('children')
                ->whereNull('parent_id')
                ->orderBy('sort_order', 'asc')
                ->get()
        );
    }

    /**
     * Store a newly created menu item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:menu_items,id',
            'label' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'column_group' => 'nullable|in:left,highlights',
            'image_url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if (! isset($validated['sort_order'])) {
            $maxSort = MenuItem::where('parent_id', $validated['parent_id'] ?? null)->max('sort_order');
            $validated['sort_order'] = ($maxSort !== null) ? $maxSort + 1 : 0;
        }

        $menuItem = MenuItem::create($validated);
        Cache::flush();

        return response()->json($menuItem, 201);
    }

    /**
     * Display the specified menu item.
     */
    public function show(MenuItem $menuItem)
    {
        return response()->json($menuItem->load('children'));
    }

    /**
     * Update the specified menu item.
     */
    public function update(Request $request, MenuItem $menuItem)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:menu_items,id',
            'label' => 'sometimes|required|string|max:255',
            'url' => 'nullable|string|max:255',
            'column_group' => 'nullable|in:left,highlights',
            'image_url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $menuItem->update($validated);
        Cache::flush();

        return response()->json($menuItem);
    }

    /**
     * Batch update sort orders.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menu_items,id',
            'items.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->items as $itemData) {
            MenuItem::where('id', $itemData['id'])->update(['sort_order' => $itemData['sort_order']]);
        }

        Cache::flush();

        return response()->json(['message' => 'Menu reordered successfully']);
    }

    /**
     * Remove the specified menu item.
     */
    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();
        Cache::flush();

        return response()->json(['message' => 'Menu item deleted successfully']);
    }
}
