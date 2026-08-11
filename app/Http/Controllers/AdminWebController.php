<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Inertia\Inertia;

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

        // Dashboard Statistics
        $totalOrders = \App\Models\Order::count();
        $inWayOrders = \App\Models\Order::whereIn('status', ['processing', 'dispatched'])->count();
        $successOrders = \App\Models\Order::where('status', 'completed')->count();
        $returnOrders = \App\Models\Order::whereIn('status', ['returned', 'cancelled'])->count();
        $totalCustomers = \App\Models\Order::distinct('customer_phone')->count('customer_phone');
        
        $monthlyRevenue = (float) (\App\Models\Order::where('status', 'completed')
                            ->whereMonth('created_at', date('m'))
                            ->whereYear('created_at', date('Y'))
                            ->sum('total_amount'));
                            
        $avgOrderValue = (float) (\App\Models\Order::where('status', 'completed')->avg('total_amount') ?? 0);

        $recentOrders = \App\Models\Order::with('items')->orderBy('created_at', 'desc')->take(50)->get()->map(function($o) {
            $prodSummary = $o->items->map(function($i) { return $i->product_name . ' (x' . $i->quantity . ')'; })->join(', ');
            return [
                'id' => (string) $o->order_number,
                'client' => $o->customer_name . ' (' . $o->customer_phone . ')',
                'prod' => $prodSummary ?: 'No items',
                'amt' => (float) $o->total_amount,
                'status' => ucfirst(str_replace('_', ' ', $o->status))
            ];
        });

        return Inertia::render('Admin/Dashboard', [
            'totalOrders' => $totalOrders,
            'inWayOrders' => $inWayOrders,
            'successOrders' => $successOrders,
            'returnOrders' => $returnOrders,
            'totalCustomers' => $totalCustomers,
            'monthlyRevenue' => $monthlyRevenue,
            'avgOrderValue' => $avgOrderValue,
            'recentOrders' => $recentOrders,
            'productCount' => $productCount,
        ]);
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

    public function purchaseList()
    {
        $purchases = Purchase::with('supplier')->orderBy('created_at', 'desc')->get();
        return Inertia::render('Admin/Purchases/Index', [
            'purchases' => $purchases,
        ]);
    }

    public function purchaseAdd()
    {
        $suppliers = Supplier::orderBy('company_name')->get();
        $products = Product::with(['category', 'images'])->orderBy('created_at', 'desc')->get();
        return Inertia::render('Admin/Purchases/Create', [
            'suppliers' => $suppliers,
            'products' => $products,
        ]);
    }

    public function productList(Request $request)
    {
        $query = Product::with(['category', 'images'])->orderBy('created_at', 'desc');
        
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%");
        }
        
        if ($request->has('category') && $request->get('category') != 'All') {
            $cat = Category::where('name', $request->get('category'))->first();
            if ($cat) {
                $query->where('category_id', $cat->id);
            }
        }
        
        $products = $query->get();
        $categories = Category::all();

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function productAdd()
    {
        $product = new Product();
        $categories = Category::all();
        $variants = \App\Models\Variant::orderBy('name')->get();
        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
            'categories' => $categories,
            'variants' => $variants,
        ]);
    }

    public function customers()
    {
        $customers = Customer::orderBy('created_at', 'desc')->get();
        return Inertia::render('Admin/Customers', [
            'customers' => $customers,
        ]);
    }

    public function suppliers()
    {
        $suppliers = Supplier::orderBy('created_at', 'desc')->get();
        return Inertia::render('Admin/Suppliers', [
            'suppliers' => $suppliers,
        ]);
    }

    public function settings()
    {
        return Inertia::render('Admin/Settings/Index');
    }

    public function apiPayment()
    {
        return Inertia::render('Admin/Settings/ApiPayment');
    }

    public function apiSms()
    {
        $siteSettings = \App\Models\Setting::pluck('value', 'key')->all();
        return Inertia::render('Admin/Settings/ApiSms', [
            'siteSettings' => $siteSettings
        ]);
    }

    public function apiCourier()
    {
        return Inertia::render('Admin/Courier');
    }

    public function seoMeta()
    {
        $siteSettings = Setting::pluck('value', 'key')->all();
        return Inertia::render('Admin/Seo/Meta', [
            'siteSettings' => $siteSettings
        ]);
    }

    public function seoMarketing()
    {
        $siteSettings = Setting::pluck('value', 'key')->all();
        return Inertia::render('Admin/Seo/Marketing', [
            'siteSettings' => $siteSettings
        ]);
    }

    public function seoPing()
    {
        return Inertia::render('Admin/Seo/Ping');
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
        return Inertia::render('Admin/Categories', [
            'categories' => $categories,
        ]);
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


    public function editProduct($id)
    {
        $product = Product::with(['category', 'images', 'variants'])->findOrFail($id);
        $categories = Category::all();
        $variants = \App\Models\Variant::orderBy('name')->get();
        $siteSettings = Setting::pluck('value', 'key')->all();
        return \Inertia\Inertia::render('Admin/Products/Edit', [
            'product' => $product,
            'categories' => $categories,
            'variants' => $variants,
            'siteSettings' => $siteSettings
        ]);
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
        
        // Sync Variants
        if ($request->has('variants') && is_array($request->variants)) {
            $syncData = [];
            foreach ($request->variants as $variant) {
                if (!empty($variant['variant_id'])) {
                    $syncData[$variant['variant_id']] = [
                        'price' => $variant['price'] ?? $product->price,
                        'stock' => $variant['stock'] ?? 0,
                    ];
                }
            }
            $product->variants()->sync($syncData);
        }

        Cache::flush();

        if ($request->wantsJson()) {
            return response()->json($product, 201);
        }

        return redirect('/admin/products')->with('success', 'Product created successfully in database!');
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
        
        // Sync Variants
        if ($request->has('variants') && is_array($request->variants)) {
            $syncData = [];
            foreach ($request->variants as $variant) {
                if (!empty($variant['variant_id'])) {
                    $syncData[$variant['variant_id']] = [
                        'price' => $variant['price'] ?? $product->price,
                        'stock' => $variant['stock'] ?? 0,
                    ];
                }
            }
            $product->variants()->sync($syncData);
        } else if ($request->has('variants') && empty($request->variants)) {
            // Clear variants if empty array was sent
            $product->variants()->sync([]);
        }

        Cache::flush();

        return redirect('/admin/products')->with('success', 'Product updated successfully in database!');
    }

    public function destroyProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        Cache::flush();

        return redirect('/admin/products')->with('success', 'Product removed from database.');
    }

    public function createOrder()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::with(['category', 'images'])
            ->orderBy('created_at', 'desc')
            ->get();
        return Inertia::render('Admin/Orders/Create', [
            'products' => $products,
            'customers' => $customers,
        ]);
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

        return Inertia::render('Admin/Orders/Index', [
            'orders' => $orders->items(),
        ]);
    }
    public function invoiceOrder($id)
    {
        $order = Order::with('items')->findOrFail($id);
        $siteSettings = Setting::pluck('value', 'key')->all();
        return Inertia::render('Admin/Orders/Invoice', [
            'order' => $order,
            'siteSettings' => $siteSettings,
        ]);
    }

    public function editOrder($id)
    {
        $order = Order::with('items')->findOrFail($id);
        $products = Product::with(['variants', 'images'])->orderBy('name')->get();
        return Inertia::render('Admin/Orders/Edit', [
            'order' => $order,
            'products' => $products,
        ]);
    }

    public function updateOrder(Request $request, $id)
    {
        $order = Order::with('items')->findOrFail($id);

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:50',
            'customer_email' => 'nullable|email|max:255',
            'shipping_address' => 'required|string',
            'city' => 'nullable|string|max:100',
            'payment_method' => 'required|string',
            'status' => 'required|string|in:pending,processing,completed,cancelled',
            'payment_status' => 'required|string|in:unpaid,paid,refunded',
            'shipping_cost' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.size' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($order, $validated) {
            // Restore previous stock
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product && $order->status !== 'cancelled') {
                    $product->increment('stock', $item->quantity);
                }
            }

            // Update order info
            $order->update([
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'],
                'shipping_address' => $validated['shipping_address'],
                'city' => $validated['city'] ?? $order->city,
                'payment_method' => $validated['payment_method'],
                'status' => $validated['status'],
                'payment_status' => $validated['payment_status'],
                'shipping_cost' => $validated['shipping_cost'] ?? 0,
                'discount_amount' => $validated['discount_amount'] ?? 0,
            ]);

            // Clear old items and insert new ones
            $order->items()->delete();

            $subtotal = 0;
            foreach ($validated['items'] as $itemData) {
                $product = Product::find($itemData['product_id']);
                $lineTotal = $itemData['unit_price'] * $itemData['quantity'];
                $subtotal += $lineTotal;

                $order->items()->create([
                    'product_id' => $itemData['product_id'],
                    'product_name' => $product->name,
                    'size' => $itemData['size'] ?? '100ml',
                    'unit_price' => $itemData['unit_price'],
                    'quantity' => $itemData['quantity'],
                    'total_price' => $lineTotal,
                ]);

                // Deduct new stock if not cancelled
                if ($order->status !== 'cancelled' && $product) {
                    $product->decrement('stock', $itemData['quantity']);
                }
            }

            // Calculate new total
            $order->subtotal = $subtotal;
            $order->total_amount = $subtotal + $order->shipping_cost - $order->discount_amount;
            $order->save();
        });

        return redirect('/admin/orders')->with('success', "Order #{$order->order_number} updated successfully.");
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
        $order = Order::with('items.product')->findOrFail($id);
        $orderNumber = $order->order_number;
        
        // Restore stock
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->stock += $item->quantity;
                $item->product->save();
            }
        }
        
        $order->delete();

        return redirect()->back()->with('success', "Order #{$orderNumber} has been removed and stock restored.");
    }
}



