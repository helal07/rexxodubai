<?php

use App\Http\Controllers\AdminCourierController;
use App\Http\Controllers\AdminWebController;
use App\Http\Controllers\CourierChargeController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SmsController;
use Illuminate\Support\Facades\Route;

use Inertia\Inertia;
use App\Models\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\CampaignController;

// Public Frontend Routes (Inertia)
Route::get('/', function () {
    $categories = \App\Models\Category::with(['products' => function($query) {
        $query->with('images')->take(8);
    }])->whereHas('products')->take(4)->get();

    return Inertia::render('Home', [
        'featuredProducts' => Product::with('images')->where('is_featured', true)->take(8)->get(),
        'newArrivals' => Product::with('images')->where('is_new_arrival', true)->take(8)->get(),
        'categoriesWithProducts' => $categories,
        'activeCampaigns' => \App\Models\Campaign::with(['products' => function($query) {
            $query->with('images')->take(4);
        }])->where('is_active', true)->orderBy('created_at', 'desc')->get(),
    ]);
});
Route::get('/about', function () {
    return Inertia::render('About');
});
Route::get('/contact', function () {
    return Inertia::render('Contact');
});
Route::get('/checkout', function () {
    return Inertia::render('Checkout');
});
Route::get('/pages/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->name('page.show');

Route::get('/perfumes', function (Request $request) {
    $query = Product::with(['images', 'category.parent']);
    $isFallback = false;

    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('scent_family', 'like', "%{$search}%")
              ->orWhere('notes_top', 'like', "%{$search}%")
              ->orWhere('notes_heart', 'like', "%{$search}%")
              ->orWhere('notes_base', 'like', "%{$search}%")
              ->orWhere('short_description', 'like', "%{$search}%");
        });
    }

    if ($request->has('category') && !empty($request->input('category'))) {
        $catSlug = $request->input('category');
        $query->where(function ($q) use ($catSlug) {
            $hasParent = \Illuminate\Support\Facades\Schema::hasColumn('categories', 'parent_id');
            $q->whereHas('category', function ($sub) use ($catSlug, $hasParent) {
                $sub->where('slug', $catSlug);
                if ($hasParent) {
                    $sub->orWhereHas('parent', function ($p) use ($catSlug) {
                        $p->where('slug', $catSlug);
                    });
                }
            });

            if (in_array($catSlug, ['men', 'men-perfumes', 'men-fragrances'])) {
                $q->orWhereIn('gender', ['men', 'unisex']);
            } elseif (in_array($catSlug, ['women', 'women-perfumes', 'women-fragrances'])) {
                $q->orWhereIn('gender', ['women', 'unisex']);
            } elseif (in_array($catSlug, ['kids', 'kids-perfume'])) {
                $q->orWhere('gender', 'kids');
            }
        });
    }

    if ($request->has('gender') && $request->input('gender') !== 'all') {
        $query->whereIn('gender', [$request->input('gender'), 'unisex']);
    }

    $products = $query->get();
    
    // If specific filter/search yields no result, provide fallback recommendations
    if (($request->has('search') || $request->has('category')) && $products->isEmpty()) {
        $products = Product::with(['images', 'category'])->where('is_featured', true)->take(4)->get();
        $isFallback = true;
    }

    return Inertia::render('Perfumes', [
        'products' => $products,
        'filters' => $request->all(),
        'isFallback' => $isFallback,
    ]);
});
Route::get('/product/{slug}', function ($slug) {
    $product = Product::with(['images', 'category.parent', 'variants'])->where('slug', $slug)->firstOrFail();
    $related = Product::with('images')
        ->where('id', '!=', $product->id)
        ->where(function ($q) use ($product) {
            $q->where('scent_family', $product->scent_family)
              ->orWhere('gender', $product->gender);
        })
        ->take(4)
        ->get();

    return Inertia::render('Product/Show', [
        'product' => $product,
        'related' => $related,
    ]);
});
// Admin Authentication Routes
Route::get('/admin/login', [\App\Http\Controllers\Auth\AdminLoginController::class, 'create'])->name('login');
Route::post('/admin/login', [\App\Http\Controllers\Auth\AdminLoginController::class, 'store']);
Route::post('/admin', [\App\Http\Controllers\Auth\AdminLoginController::class, 'store']);
Route::get('/login', function () {
    return redirect('/admin');
});

// Ã¢â€â‚¬Ã¢â€â‚¬ Public SEO routes (must be before auth middleware) Ã¢â€â‚¬Ã¢â€â‚¬
Route::get('/sitemap.xml', [SeoController::class, 'sitemap']);
Route::get('/robots.txt',  [SeoController::class, 'robots']);

// Admin Root Route: Shows Login if guest, or Dashboard if authenticated admin
Route::get('/admin', function () {
    if (!\Illuminate\Support\Facades\Auth::check() || !\Illuminate\Support\Facades\Auth::user()->is_admin) {
        return \Inertia\Inertia::render('Auth/AdminLogin');
    }
    return app(\App\Http\Controllers\AdminWebController::class)->dashboard();
});

// All Backend Admin Panel Web Routes (Protected)
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminWebController::class, 'dashboard']);
    Route::get('/admin/categories', [AdminWebController::class, 'categories']);
    Route::get('/admin/products/list', [AdminWebController::class, 'productList']);
    Route::get('/admin/products/add', [AdminWebController::class, 'productAdd']);
    Route::get('/admin/create-order', [AdminWebController::class, 'createOrder']);
    Route::get('/admin/orders', [AdminWebController::class, 'orders']);
    Route::get('/admin/orders/{id}/invoice', [AdminWebController::class, 'invoiceOrder']);
    Route::get('/admin/orders/{id}/edit', [AdminWebController::class, 'editOrder']);
    Route::put('/admin/orders/{id}', [AdminWebController::class, 'updateOrder']);
    Route::post('/admin/orders/{id}/status', [AdminWebController::class, 'updateOrderStatus']);
    Route::put('/admin/orders/{id}/status', [AdminWebController::class, 'updateOrderStatus']);
    Route::delete('/admin/orders/{id}', [AdminWebController::class, 'destroyOrder']);

    // Newly Extracted Dashboard Sections
    Route::get('/admin/purchases', [AdminWebController::class, 'purchaseList']);
    Route::get('/admin/purchases/list', [AdminWebController::class, 'purchaseList']);
    Route::get('/admin/purchases/add', [AdminWebController::class, 'purchaseAdd']);
    Route::get('/admin/customers', [AdminWebController::class, 'customers']);
    Route::get('/admin/suppliers', [AdminWebController::class, 'suppliers']);
    // Settings & CMS Routes
    Route::get('/admin/settings', [AdminWebController::class, 'settings']);
    Route::get('/admin/cms', [\App\Http\Controllers\FrontendCmsController::class, 'index']);
    Route::post('/admin/cms', [\App\Http\Controllers\FrontendCmsController::class, 'update']);
    
    // Split API Settings
    Route::get('/admin/api-settings/payment', [AdminWebController::class, 'apiPayment']);
    Route::get('/admin/api-settings/sms', [AdminWebController::class, 'apiSms']);
    Route::get('/admin/api-settings/courier', [AdminWebController::class, 'apiCourier']);
    
    // Split SEO Settings
    Route::get('/admin/seo/meta', [AdminWebController::class, 'seoMeta']);
    Route::get('/admin/seo/marketing', [AdminWebController::class, 'seoMarketing']);
    Route::get('/admin/seo/ping', [AdminWebController::class, 'seoPing']);

    // Courier Hub & Live API Routes
    Route::get('/admin/courier', [AdminCourierController::class, 'index']);
    Route::post('/admin/courier/settings', [AdminCourierController::class, 'saveSettings']);
    Route::post('/admin/courier/save/{key}', [AdminCourierController::class, 'saveSingleCourier']);
    Route::post('/admin/courier/test-connection', [AdminCourierController::class, 'testConnection']);
    Route::post('/admin/courier/dispatch', [AdminCourierController::class, 'dispatchOrder']);
    Route::get('/admin/courier/track/{id}', [AdminCourierController::class, 'trackOrder']);
    Route::get('/admin/run-migrations', [AdminCourierController::class, 'runMigrations']);
    Route::get('/admin/courier/run-migrations', [AdminCourierController::class, 'runMigrations']);

    // Courier Charge Settings Routes
    Route::get('/admin/courier-charges', [CourierChargeController::class, 'index']);
    Route::post('/admin/courier-charges', [CourierChargeController::class, 'store']);
    Route::put('/admin/courier-charges/{id}', [CourierChargeController::class, 'update']);
    Route::delete('/admin/courier-charges/{id}', [CourierChargeController::class, 'destroy']);
    Route::post('/admin/courier-charges/bulk-update', [CourierChargeController::class, 'bulkUpdate']);

    // Menu Builder CRUD Routes
    Route::post('/admin/menus', [AdminWebController::class, 'storeMenu']);
    Route::put('/admin/menus/{id}', [AdminWebController::class, 'updateMenu']);
    Route::delete('/admin/menus/{id}', [AdminWebController::class, 'destroyMenu']);

    // Category & Subcategory CRUD Routes
    Route::post('/admin/categories', [AdminWebController::class, 'storeCategory']);
    Route::put('/admin/categories/{id}', [AdminWebController::class, 'updateCategory']);
    Route::delete('/admin/categories/{id}', [AdminWebController::class, 'destroyCategory']);
    
    // Custom Pages CRUD Routes
    Route::post('/admin/pages', [\App\Http\Controllers\PageController::class, 'store']);
    Route::put('/admin/pages/{id}', [\App\Http\Controllers\PageController::class, 'update']);
    Route::delete('/admin/pages/{id}', [\App\Http\Controllers\PageController::class, 'destroy']);

    // Product Catalog CRUD Routes
    Route::get('/admin/products', [AdminWebController::class, 'productList']);
    Route::get('/admin/products/{id}/edit', [AdminWebController::class, 'editProduct']);
    Route::post('/admin/products', [AdminWebController::class, 'storeProduct']);
    Route::put('/admin/products/{id}', [AdminWebController::class, 'updateProduct']);
    Route::post('/admin/products/{id}', [AdminWebController::class, 'updateProduct']);
    Route::delete('/admin/products/{id}', [AdminWebController::class, 'destroyProduct']);

    // Logout Routes
    Route::post('/logout', [\App\Http\Controllers\Auth\AdminLoginController::class, 'destroy'])->name('logout');
    Route::get('/admin/logout', [\App\Http\Controllers\Auth\AdminLoginController::class, 'destroy']);
    Route::post('/admin/logout', [\App\Http\Controllers\Auth\AdminLoginController::class, 'destroy']);

    // Settings Route
    Route::post('/admin/settings', [\App\Http\Controllers\SettingController::class, 'store'])->name('admin.settings.store');

    // Campaigns
    Route::post('/admin/campaigns/{campaign}/toggle', [CampaignController::class, 'toggle'])->name('campaigns.toggle');
    Route::resource('admin/campaigns', CampaignController::class);

    // Variants
    Route::get('/admin/variants', [\App\Http\Controllers\Admin\VariantController::class, 'index']);
    Route::post('/admin/variants', [\App\Http\Controllers\Admin\VariantController::class, 'store']);
    Route::put('/admin/variants/{id}', [\App\Http\Controllers\Admin\VariantController::class, 'update']);
    Route::delete('/admin/variants/{id}', [\App\Http\Controllers\Admin\VariantController::class, 'destroy']);

    // SMS Gateway Routes
    Route::post('/admin/sms/test', [SmsController::class, 'testConnection'])->name('admin.sms.test');

    // SEO — Sitemap & Robots Generator Routes
    Route::get('/admin/seo/status',              [SeoController::class, 'status'])->name('admin.seo.status');
    Route::post('/admin/seo/generate-sitemap',   [SeoController::class, 'generateSitemap'])->name('admin.seo.sitemap');
    Route::post('/admin/seo/generate-robots',    [SeoController::class, 'generateRobots'])->name('admin.seo.robots');
    Route::post('/admin/seo/ping-search-engines',[SeoController::class, 'pingSearchEngines'])->name('admin.seo.ping');

    // User Profile & Staff Management Routes
    Route::get('/admin/profile', [\App\Http\Controllers\AdminProfileController::class, 'index']);

    Route::get('/admin/users', [\App\Http\Controllers\AdminProfileController::class, 'users']);
    Route::post('/admin/profile/update', [\App\Http\Controllers\AdminProfileController::class, 'updateProfile']);
    Route::post('/admin/profile/remove-avatar', [\App\Http\Controllers\AdminProfileController::class, 'removeAvatar']);
    Route::post('/admin/profile/password', [\App\Http\Controllers\AdminProfileController::class, 'updatePassword']);
    Route::post('/admin/users', [\App\Http\Controllers\AdminProfileController::class, 'storeUser']);
    Route::put('/admin/users/{id}', [\App\Http\Controllers\AdminProfileController::class, 'updateUser']);
    Route::delete('/admin/users/{id}', [\App\Http\Controllers\AdminProfileController::class, 'deleteUser']);

    // Customer API Routes
    Route::post('/admin/api/customers', [\App\Http\Controllers\Admin\CustomerController::class, 'store']);
    Route::put('/admin/api/customers/{id}', [\App\Http\Controllers\Admin\CustomerController::class, 'update']);
    Route::delete('/admin/api/customers/{id}', [\App\Http\Controllers\Admin\CustomerController::class, 'destroy']);

    // Supplier API Routes
    Route::post('/admin/api/suppliers', [\App\Http\Controllers\Admin\SupplierController::class, 'store']);
    Route::put('/admin/api/suppliers/{id}', [\App\Http\Controllers\Admin\SupplierController::class, 'update']);
    Route::delete('/admin/api/suppliers/{id}', [\App\Http\Controllers\Admin\SupplierController::class, 'destroy']);

    // Purchase API Routes
    Route::post('/admin/api/purchases', [\App\Http\Controllers\Admin\PurchaseController::class, 'store']);
    Route::delete('/admin/api/purchases/{id}', [\App\Http\Controllers\Admin\PurchaseController::class, 'destroy']);

    // Sale API Routes
    Route::post('/admin/api/sales', [\App\Http\Controllers\Admin\SaleController::class, 'store']);
    Route::delete('/admin/api/sales/{id}', [\App\Http\Controllers\Admin\SaleController::class, 'destroy']);

    // Role & Permission Routes
    Route::get('/admin/roles', [\App\Http\Controllers\Admin\RoleController::class, 'index']);
    Route::post('/admin/roles', [\App\Http\Controllers\Admin\RoleController::class, 'store']);
    Route::post('/admin/roles/{id}/sync-permissions', [\App\Http\Controllers\Admin\RoleController::class, 'syncPermission']);
    Route::post('/admin/api/roles', [\App\Http\Controllers\Admin\RoleController::class, 'store']);
    Route::put('/admin/api/roles/{id}', [\App\Http\Controllers\Admin\RoleController::class, 'update']);
    Route::delete('/admin/api/roles/{id}', [\App\Http\Controllers\Admin\RoleController::class, 'destroy']);
    Route::post('/admin/api/roles/assign', [\App\Http\Controllers\Admin\RoleController::class, 'assignRole']);
});


// Setup / Migration helper route (Exempt from session middleware)
Route::withoutMiddleware([
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    \App\Http\Middleware\HandleInertiaRequests::class,
    \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
])->get('/run-migrations', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $migrateOutput = \Illuminate\Support\Facades\Artisan::output();

        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        $seedOutput = \Illuminate\Support\Facades\Artisan::output();

        \Illuminate\Support\Facades\Artisan::call('storage:link');
        $storageOutput = \Illuminate\Support\Facades\Artisan::output();

        \Illuminate\Support\Facades\Artisan::call('optimize:clear');

        return '<div style="font-family: sans-serif; padding: 40px; background: #0f172a; color: #f8fafc; min-height: 100vh;">' .
               '<h1 style="color: #4ade80;">Setup & Migrations Completed Successfully!</h1>' .
               '<pre style="background: #1e293b; padding: 15px; border-radius: 8px; overflow: auto; color: #e2e8f0;">' . htmlspecialchars($migrateOutput . "\n" . $seedOutput . "\n" . $storageOutput) . '</pre>' .
               '<p style="margin-top: 20px;"><a href="/" style="color: #38bdf8; font-weight: bold; font-size: 18px; text-decoration: none;">&larr; Return to Website Home</a></p>' .
               '</div>';
    } catch (\Throwable $e) {
        return '<div style="font-family: sans-serif; padding: 40px; background: #0f172a; color: #f8fafc;">' .
               '<h1 style="color: #f87171;">Migration Error</h1>' .
               '<pre>' . htmlspecialchars($e->getMessage() . "\n" . $e->getTraceAsString()) . '</pre>' .
               '</div>';
    }
});


