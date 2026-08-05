<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
*/

// Menu public tree for Header & MegaMenu navigation
Route::get('/menu', [MenuItemController::class, 'publicTree']);

// Public catalog & product routes
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{slug}', [CategoryController::class, 'show']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);

// Settings public endpoints
Route::get('/settings', [SettingController::class, 'index']);
Route::post('/settings', [SettingController::class, 'store']);

// Checkout & order creation
Route::post('/orders', [OrderController::class, 'store']);

// Clear Cache System
Route::post('/clear-cache', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return response()->json(['message' => 'System cache cleared successfully']);
});

// Setup Migrations API Endpoint
Route::get('/run-migrations', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $migrate = \Illuminate\Support\Facades\Artisan::output();
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        $seed = \Illuminate\Support\Facades\Artisan::output();
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        return response()->json([
            'status' => 'success',
            'message' => 'Migrations, seeds, and storage link completed successfully!',
            'details' => $migrate . "\n" . $seed
        ]);
    } catch (\Throwable $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
});


// Admin Auth Login
Route::post('/admin/login', [AuthController::class, 'login'])->name('login');
Route::get('/admin/login', function () {
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| Protected Admin API Routes (Sanctum Auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Menu Builder CRUD + Reorder
    Route::patch('/menu-items/reorder', [MenuItemController::class, 'reorder']);
    Route::apiResource('menu-items', MenuItemController::class);

    // Categories CRUD
    Route::apiResource('categories', CategoryController::class);

    // Products CRUD
    Route::apiResource('products', ProductController::class);

    // Orders Management
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);
});
