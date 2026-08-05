<?php

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
    $query = Product::with('images');
    $isFallback = false;

    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where('name', 'like', "%{$search}%")
              ->orWhere('scent_family', 'like', "%{$search}%")
              ->orWhere('notes_top', 'like', "%{$search}%");
    }

    if ($request->has('gender') && $request->input('gender') !== 'all') {
        $query->whereIn('gender', [$request->input('gender'), 'unisex']);
    }

    $products = $query->get();
    
    // If search yields no result, provide fallback recommendations
    if ($request->has('search') && $products->isEmpty()) {
        $products = Product::with('images')->where('is_featured', true)->take(4)->get();
        $isFallback = true;
    }

    return Inertia::render('Perfumes', [
        'products' => $products,
        'filters' => $request->all(),
        'isFallback' => $isFallback,
    ]);
});
Route::get('/product/{slug}', function ($slug) {
    $product = Product::with(['images', 'category'])->where('slug', $slug)->firstOrFail();
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
    Route::get('/admin/products', [AdminWebController::class, 'products']);
    Route::get('/admin/orders', [AdminWebController::class, 'dashboard']);
    Route::get('/admin/courier', [AdminWebController::class, 'dashboard']);

    // Menu Builder CRUD Routes
    Route::post('/admin/menus', [AdminWebController::class, 'storeMenu']);
    Route::put('/admin/menus/{id}', [AdminWebController::class, 'updateMenu']);
    Route::delete('/admin/menus/{id}', [AdminWebController::class, 'destroyMenu']);

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

// Setup / Migration helper route
Route::get('/run-migrations', function () {
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
               '<pre style="background: #1e293b; padding: 15px; border-radius: 8px; overflow: auto;">' . htmlspecialchars($migrateOutput . "\n" . $seedOutput . "\n" . $storageOutput) . '</pre>' .
               '<p style="margin-top: 20px;"><a href="/" style="color: #38bdf8; font-weight: bold; font-size: 18px; text-decoration: none;">&larr; Return to Website Home</a></p>' .
               '</div>';
    } catch (\Throwable $e) {
        return '<div style="font-family: sans-serif; padding: 40px; background: #0f172a; color: #f8fafc;">' .
               '<h1 style="color: #f87171;">Migration Error</h1>' .
               '<p>' . htmlspecialchars($e->getMessage()) . '</p>' .
               '</div>';
    }
});
