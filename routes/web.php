<?php

use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\VariantController;
use App\Http\Controllers\AdminCourierController;
use App\Http\Controllers\AdminLandingPageController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AdminWebController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\CourierChargeController;
use App\Http\Controllers\FrontendCmsController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SmsController;
use App\Http\Middleware\HandleInertiaRequests;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Inertia\Inertia;

// Public Frontend Routes (Inertia)
Route::get('/', function () {
    $categories = Category::with(['products' => function ($query) {
        $query->with('images')->take(8);
    }])->whereHas('products')->take(4)->get();

    return Inertia::render('Home', [
        'featuredProducts' => Product::with('images')->where('is_featured', true)->take(8)->get(),
        'newArrivals' => Product::with('images')->where('is_new_arrival', true)->take(8)->get(),
        'categoriesWithProducts' => $categories,
        'activeCampaigns' => Campaign::with(['products' => function ($query) {
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
Route::get('/pages/{slug}', [PageController::class, 'show'])->name('page.show');

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

    if ($request->has('category') && ! empty($request->input('category'))) {
        $catSlug = $request->input('category');
        $query->where(function ($q) use ($catSlug) {
            $hasParent = Schema::hasColumn('categories', 'parent_id');
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
Route::get('/admin/login', [AdminLoginController::class, 'create'])->name('login');
Route::post('/admin/login', [AdminLoginController::class, 'store']);
Route::post('/admin', [AdminLoginController::class, 'store']);
Route::get('/login', function () {
    return redirect('/admin');
});

// Ã¢â€â‚¬Ã¢â€â‚¬ Public SEO routes (must be before auth middleware) Ã¢â€â‚¬Ã¢â€â‚¬
Route::get('/sitemap.xml', [SeoController::class, 'sitemap']);
Route::get('/robots.txt', [SeoController::class, 'robots']);

// Public Landing Page Route & Live Preview
Route::get('/landing-page-preview', [LandingPageController::class, 'preview'])->name('landing-page.preview');
Route::get('/landing-page/{slug}', [LandingPageController::class, 'show'])->name('landing-page.show');

// Admin Root Route: Shows Login if guest, or Dashboard if authenticated admin
Route::get('/admin', function () {
    if (! Auth::check() || ! Auth::user()->is_admin) {
        return Inertia::render('Auth/AdminLogin');
    }

    return app(AdminWebController::class)->dashboard();
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
    Route::get('/admin/cms', [FrontendCmsController::class, 'index']);
    Route::post('/admin/cms', [FrontendCmsController::class, 'update']);

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
    Route::post('/admin/pages', [PageController::class, 'store']);
    Route::put('/admin/pages/{id}', [PageController::class, 'update']);
    Route::delete('/admin/pages/{id}', [PageController::class, 'destroy']);

    // Product Catalog CRUD Routes
    Route::get('/admin/products', [AdminWebController::class, 'productList']);
    Route::get('/admin/products/{id}/edit', [AdminWebController::class, 'editProduct']);
    Route::post('/admin/products', [AdminWebController::class, 'storeProduct']);
    Route::put('/admin/products/{id}', [AdminWebController::class, 'updateProduct']);
    Route::post('/admin/products/{id}', [AdminWebController::class, 'updateProduct']);
    Route::delete('/admin/products/{id}', [AdminWebController::class, 'destroyProduct']);

    // Logout Routes
    Route::post('/logout', [AdminLoginController::class, 'destroy'])->name('logout');
    Route::get('/admin/logout', [AdminLoginController::class, 'destroy']);
    Route::post('/admin/logout', [AdminLoginController::class, 'destroy']);

    // Settings Route
    Route::post('/admin/settings', [SettingController::class, 'store'])->name('admin.settings.store');

    // Campaigns
    Route::post('/admin/campaigns/{campaign}/toggle', [CampaignController::class, 'toggle'])->name('campaigns.toggle');
    Route::resource('admin/campaigns', CampaignController::class);

    // Variants
    Route::get('/admin/variants', [VariantController::class, 'index']);
    Route::post('/admin/variants', [VariantController::class, 'store']);
    Route::put('/admin/variants/{id}', [VariantController::class, 'update']);
    Route::delete('/admin/variants/{id}', [VariantController::class, 'destroy']);

    // SMS Gateway Routes
    Route::post('/admin/sms/test', [SmsController::class, 'testConnection'])->name('admin.sms.test');

    // SEO — Sitemap & Robots Generator Routes
    Route::get('/admin/seo/status', [SeoController::class, 'status'])->name('admin.seo.status');
    Route::post('/admin/seo/generate-sitemap', [SeoController::class, 'generateSitemap'])->name('admin.seo.sitemap');
    Route::post('/admin/seo/generate-robots', [SeoController::class, 'generateRobots'])->name('admin.seo.robots');
    Route::post('/admin/seo/ping-search-engines', [SeoController::class, 'pingSearchEngines'])->name('admin.seo.ping');

    // User Profile & Staff Management Routes
    Route::get('/admin/profile', [AdminProfileController::class, 'index']);

    Route::get('/admin/users', [AdminProfileController::class, 'users']);
    Route::post('/admin/profile/update', [AdminProfileController::class, 'updateProfile']);
    Route::post('/admin/profile/remove-avatar', [AdminProfileController::class, 'removeAvatar']);
    Route::post('/admin/profile/password', [AdminProfileController::class, 'updatePassword']);
    Route::post('/admin/users', [AdminProfileController::class, 'storeUser']);
    Route::put('/admin/users/{id}', [AdminProfileController::class, 'updateUser']);
    Route::delete('/admin/users/{id}', [AdminProfileController::class, 'deleteUser']);

    // Customer API Routes
    Route::post('/admin/api/customers', [CustomerController::class, 'store']);
    Route::put('/admin/api/customers/{id}', [CustomerController::class, 'update']);
    Route::delete('/admin/api/customers/{id}', [CustomerController::class, 'destroy']);

    // Supplier API Routes
    Route::post('/admin/api/suppliers', [SupplierController::class, 'store']);
    Route::put('/admin/api/suppliers/{id}', [SupplierController::class, 'update']);
    Route::delete('/admin/api/suppliers/{id}', [SupplierController::class, 'destroy']);

    // Purchase API Routes
    Route::post('/admin/api/purchases', [PurchaseController::class, 'store']);
    Route::delete('/admin/api/purchases/{id}', [PurchaseController::class, 'destroy']);

    // Sale API Routes
    Route::post('/admin/api/sales', [SaleController::class, 'store']);
    Route::delete('/admin/api/sales/{id}', [SaleController::class, 'destroy']);

    // Landing Page Routes
    Route::resource('admin/landing-pages', AdminLandingPageController::class)->names('admin.landing-pages');

    // Role & Permission Routes
    Route::get('/admin/roles', [RoleController::class, 'index']);
    Route::post('/admin/roles', [RoleController::class, 'store']);
    Route::post('/admin/roles/{id}/sync-permissions', [RoleController::class, 'syncPermission']);
    Route::post('/admin/api/roles', [RoleController::class, 'store']);
    Route::put('/admin/api/roles/{id}', [RoleController::class, 'update']);
    Route::delete('/admin/api/roles/{id}', [RoleController::class, 'destroy']);
    Route::post('/admin/api/roles/assign', [RoleController::class, 'assignRole']);
});

// Setup / Migration helper route (Exempt from session middleware)
Route::withoutMiddleware([
    StartSession::class,
    ShareErrorsFromSession::class,
    HandleInertiaRequests::class,
    ValidateCsrfToken::class,
])->get('/run-migrations', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        $migrateOutput = Artisan::output();

        Artisan::call('db:seed', ['--force' => true]);
        $seedOutput = Artisan::output();

        Artisan::call('storage:link');
        $storageOutput = Artisan::output();

        Artisan::call('optimize:clear');

        return '<div style="font-family: sans-serif; padding: 40px; background: #0f172a; color: #f8fafc; min-height: 100vh;">'.
               '<h1 style="color: #4ade80;">Setup & Migrations Completed Successfully!</h1>'.
               '<pre style="background: #1e293b; padding: 15px; border-radius: 8px; overflow: auto; color: #e2e8f0;">'.htmlspecialchars($migrateOutput."\n".$seedOutput."\n".$storageOutput).'</pre>'.
               '<p style="margin-top: 20px;"><a href="/" style="color: #38bdf8; font-weight: bold; font-size: 18px; text-decoration: none;">&larr; Return to Website Home</a></p>'.
               '</div>';
    } catch (Throwable $e) {
        return '<div style="font-family: sans-serif; padding: 40px; background: #0f172a; color: #f8fafc;">'.
               '<h1 style="color: #f87171;">Migration Error</h1>'.
               '<pre>'.htmlspecialchars($e->getMessage()."\n".$e->getTraceAsString()).'</pre>'.
               '</div>';
    }
});
