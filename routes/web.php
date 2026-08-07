<?php

use App\Http\Controllers\AdminCourierController;
use App\Http\Controllers\AdminWebController;
use Illuminate\Support\Facades\Route;

use Inertia\Inertia;
use App\Models\Product;

use Illuminate\Http\Request;

// Public Frontend Routes (Inertia)
Route::get('/', function () {
    return Inertia::render('Home', [
        'featuredProducts' => Product::with('images')->where('is_featured', true)->get(),
        'newArrivals' => Product::with('images')->where('is_new_arrival', true)->get(),
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
    $product = Product::with(['images', 'category.parent'])->where('slug', $slug)->firstOrFail();
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

// Admin Root Route: Shows Login if guest, or Dashboard if authenticated admin
Route::get('/admin', function () {
    if (!\Illuminate\Support\Facades\Auth::check() || !\Illuminate\Support\Facades\Auth::user()->is_admin) {
        return view('admin.login');
    }
    return app(\App\Http\Controllers\AdminWebController::class)->dashboard();
});

// All Backend Admin Panel Web Routes (Protected)
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminWebController::class, 'dashboard']);
    Route::get('/admin/menus', [AdminWebController::class, 'menus']);
    Route::get('/admin/categories', [AdminWebController::class, 'categories']);
    Route::get('/admin/products', [AdminWebController::class, 'products']);
    Route::get('/admin/orders', [AdminWebController::class, 'orders']);
    Route::post('/admin/orders/{id}/status', [AdminWebController::class, 'updateOrderStatus']);
    Route::put('/admin/orders/{id}/status', [AdminWebController::class, 'updateOrderStatus']);
    Route::delete('/admin/orders/{id}', [AdminWebController::class, 'destroyOrder']);

    // Courier Hub & Live API Routes
    Route::get('/admin/courier', [AdminCourierController::class, 'index']);
    Route::post('/admin/courier/settings', [AdminCourierController::class, 'saveSettings']);
    Route::post('/admin/courier/save/{key}', [AdminCourierController::class, 'saveSingleCourier']);
    Route::post('/admin/courier/test-connection', [AdminCourierController::class, 'testConnection']);
    Route::post('/admin/courier/dispatch', [AdminCourierController::class, 'dispatchOrder']);
    Route::get('/admin/courier/track/{id}', [AdminCourierController::class, 'trackOrder']);
    Route::get('/admin/run-migrations', [AdminCourierController::class, 'runMigrations']);
    Route::get('/admin/courier/run-migrations', [AdminCourierController::class, 'runMigrations']);

    // Menu Builder CRUD Routes
    Route::post('/admin/menus', [AdminWebController::class, 'storeMenu']);
    Route::put('/admin/menus/{id}', [AdminWebController::class, 'updateMenu']);
    Route::delete('/admin/menus/{id}', [AdminWebController::class, 'destroyMenu']);

    // Category & Subcategory CRUD Routes
    Route::post('/admin/categories', [AdminWebController::class, 'storeCategory']);
    Route::put('/admin/categories/{id}', [AdminWebController::class, 'updateCategory']);
    Route::delete('/admin/categories/{id}', [AdminWebController::class, 'destroyCategory']);

    // Product Catalog CRUD Routes
    Route::get('/admin/products/{id}/edit', [AdminWebController::class, 'editProduct']);
    Route::post('/admin/products', [AdminWebController::class, 'storeProduct']);
    Route::put('/admin/products/{id}', [AdminWebController::class, 'updateProduct']);
    Route::post('/admin/products/{id}', [AdminWebController::class, 'updateProduct']);
    Route::delete('/admin/products/{id}', [AdminWebController::class, 'destroyProduct']);

    // Logout Routes
    Route::post('/logout', [\App\Http\Controllers\Auth\AdminLoginController::class, 'destroy'])->name('logout');
    Route::get('/admin/logout', [\App\Http\Controllers\Auth\AdminLoginController::class, 'destroy']);
    Route::post('/admin/logout', [\App\Http\Controllers\Auth\AdminLoginController::class, 'destroy']);
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

