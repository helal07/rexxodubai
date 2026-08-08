<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AdminWebController extends Controller
{
    public function dashboard()
    {
        $menuCount = MenuItem::count();
        $productCount = Product::count();
        $menuItems = MenuItem::whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->orderBy('sort_order', 'asc');
            }])
            ->orderBy('sort_order', 'asc')
            ->get();

        $products = Product::with(['category', 'images'])
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = Category::all();
        $siteSettings = Setting::pluck('value', 'key')->all();
        $users = User::orderBy('created_at', 'desc')->get();
        $currentUser = Auth::user();

        return view('admin.dashboard', compact('menuCount', 'productCount', 'menuItems', 'products', 'categories', 'siteSettings', 'users', 'currentUser'));
    }

    public function menus()
    {
        $items = MenuItem::with(['children', 'parent'])->orderBy('sort_order', 'asc')->get();
        $parentItems = MenuItem::whereNull('parent_id')->orderBy('sort_order', 'asc')->get();
        $siteSettings = Setting::pluck('value', 'key')->all();
        return view('admin.menus', compact('items', 'parentItems', 'siteSettings'));
    }

    public function storeMenu(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:menu_items,id',
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = MenuItem::max('sort_order') + 1;
        }

        MenuItem::create($validated);
        Cache::flush();

        return redirect()->back()->with('success', 'Menu item added to database successfully.');
    }

    public function updateMenu(Request $request, $id)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:menu_items,id',
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $item = MenuItem::findOrFail($id);
        $item->update($validated);
        Cache::flush();

        return redirect()->back()->with('success', 'Menu item updated in database successfully.');
    }

    public function destroyMenu($id)
    {
        $item = MenuItem::findOrFail($id);
        $item->delete();
        Cache::flush();

        return redirect()->back()->with('success', 'Menu item deleted from database.');
    }

    public function categories()
    {
        $categories = Category::with(['children', 'parent', 'products'])->orderBy('sort_order', 'asc')->get();
        $parentCategories = Category::whereNull('parent_id')->orderBy('sort_order', 'asc')->get();
        $siteSettings = Setting::pluck('value', 'key')->all();
        return view('admin.categories', compact('categories', 'parentCategories', 'siteSettings'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = Category::max('sort_order') + 1;
        }

        Category::create($validated);
        Cache::flush();

        return redirect()->back()->with('success', 'Category / Subcategory added successfully.');
    }

    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'parent_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'image_url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);
        Cache::flush();

        return redirect()->back()->with('success', 'Category / Subcategory updated successfully.');
    }

    public function destroyCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        Cache::flush();

        return redirect()->back()->with('success', 'Category deleted from database.');
    }


    public function products()
    {
        $products = Product::with(['category', 'images'])->orderBy('created_at', 'desc')->get();
        $categories = Category::all();
        $siteSettings = Setting::pluck('value', 'key')->all();
        return view('admin.products', compact('products', 'categories', 'siteSettings'));
    }

    public function editProduct($id)
    {
        $product = Product::with(['category', 'images'])->findOrFail($id);
        $categories = Category::all();
        $siteSettings = Setting::pluck('value', 'key')->all();
        return view('admin.product_edit', compact('product', 'categories', 'siteSettings'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'scent_family' => 'nullable|string|max:255',
            'concentration' => 'nullable|string|max:255',
            'sizes' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'primary_image_url' => 'nullable|string',
            'primary_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120',
            'secondary_image_url' => 'nullable|string',
            'secondary_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120',
            'gender' => 'required|in:women,men,unisex',
            'notes_top' => 'nullable|string|max:255',
            'notes_heart' => 'nullable|string|max:255',
            'notes_base' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'is_new_arrival' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'og_image_url' => 'nullable|string',
            'og_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120',
        ]);

        $uploadDir = public_path('uploads/products');
        if (!file_exists($uploadDir)) {
            @mkdir($uploadDir, 0755, true);
        }

        if ($request->hasFile('primary_image_file')) {
            $file = $request->file('primary_image_file');
            $fileName = 'prod_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $validated['primary_image_url'] = '/uploads/products/' . $fileName;
        }

        if ($request->hasFile('secondary_image_file')) {
            $file = $request->file('secondary_image_file');
            $fileName = 'prod_sec_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $validated['secondary_image_url'] = '/uploads/products/' . $fileName;
        }

        if ($request->hasFile('og_image_file')) {
            $file = $request->file('og_image_file');
            $fileName = 'prod_og_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $validated['og_image_url'] = '/uploads/products/' . $fileName;
        }

        // Handle sizes array
        if (!empty($validated['sizes'])) {
            $sizesArray = array_values(array_filter(array_map('trim', explode(',', $validated['sizes']))));
            $validated['sizes'] = $sizesArray;
        } else {
            $validated['sizes'] = ['100ml'];
        }

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(4);
        $validated['stock'] = $request->input('stock', 50);
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_new_arrival'] = $request->has('is_new_arrival');

        $product = Product::create($validated);
        Cache::flush();

        if ($request->wantsJson()) {
            return response()->json($product, 201);
        }

        return redirect('/admin/products/' . $product->id . '/edit')->with('success', 'Product created successfully in database!');
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'category_id' => 'nullable|exists:categories,id',
            'scent_family' => 'nullable|string|max:255',
            'concentration' => 'nullable|string|max:255',
            'sizes' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'primary_image_url' => 'nullable|string',
            'primary_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120',
            'secondary_image_url' => 'nullable|string',
            'secondary_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120',
            'gender' => 'required|in:women,men,unisex',
            'notes_top' => 'nullable|string|max:255',
            'notes_heart' => 'nullable|string|max:255',
            'notes_base' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'og_image_url' => 'nullable|string',
            'og_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120',
        ]);

        $uploadDir = public_path('uploads/products');
        if (!file_exists($uploadDir)) {
            @mkdir($uploadDir, 0755, true);
        }

        if ($request->hasFile('primary_image_file')) {
            $file = $request->file('primary_image_file');
            $fileName = 'prod_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $validated['primary_image_url'] = '/uploads/products/' . $fileName;
        }

        if ($request->hasFile('secondary_image_file')) {
            $file = $request->file('secondary_image_file');
            $fileName = 'prod_sec_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $validated['secondary_image_url'] = '/uploads/products/' . $fileName;
        }

        if ($request->hasFile('og_image_file')) {
            $file = $request->file('og_image_file');
            $fileName = 'prod_og_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $validated['og_image_url'] = '/uploads/products/' . $fileName;
        }

        // Handle slug
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(4);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        // Handle sizes array
        if (!empty($validated['sizes'])) {
            $sizesArray = array_values(array_filter(array_map('trim', explode(',', $validated['sizes']))));
            $validated['sizes'] = $sizesArray;
        }

        $validated['stock'] = $request->input('stock', $product->stock);
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_new_arrival'] = $request->has('is_new_arrival');

        $product->update($validated);
        Cache::flush();

        return redirect()->back()->with('success', 'Product updated successfully in database!');
    }

    public function destroyProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        Cache::flush();

        return redirect('/admin/products')->with('success', 'Product removed from database.');
    }

    public function orders(Request $request)
    {
        $query = Order::with('items')->orderBy('created_at', 'desc');

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Payment status filter
        if ($request->filled('payment_status') && $request->payment_status !== 'all') {
            $query->where('payment_status', $request->payment_status);
        }

        // Keyword Search (order number, customer name, email, phone, city)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(25)->withQueryString();
        $totalOrdersCount = Order::count();
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        $processingOrdersCount = Order::where('status', 'processing')->count();
        $completedOrdersCount = Order::where('status', 'completed')->count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $siteSettings = Setting::pluck('value', 'key')->all();

        return view('admin.orders', compact(
            'orders',
            'totalOrdersCount',
            'pendingOrdersCount',
            'processingOrdersCount',
            'completedOrdersCount',
            'totalRevenue',
            'siteSettings'
        ));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'nullable|string|in:pending,processing,completed,cancelled',
            'payment_status' => 'nullable|string|in:unpaid,paid,refunded',
        ]);

        if (isset($validated['status'])) {
            $order->status = $validated['status'];
        }

        if (isset($validated['payment_status'])) {
            $order->payment_status = $validated['payment_status'];
        }

        $order->save();

        return redirect()->back()->with('success', "Order #{$order->order_number} status updated successfully.");
    }

    public function destroyOrder($id)
    {
        $order = Order::findOrFail($id);
        $orderNumber = $order->order_number;
        $order->delete();

        return redirect()->back()->with('success', "Order #{$orderNumber} has been removed.");
    }
}

