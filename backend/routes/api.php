<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers for regular users (Storefront)
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\CategoryController as UserCategoryController;
use App\Http\Controllers\Api\ProductController as UserProductController;
use App\Http\Controllers\Api\OrderController as UserOrderController;

// Controllers for Admin Panel
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\Admin\OrderController as AdminOrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- Public Routes ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/products', [UserProductController::class, 'index']);
Route::get('/products/{product}', [UserProductController::class, 'show']);

// ✅ Here is the corrected part
Route::get('/categories', [UserCategoryController::class, 'index']);
Route::get('/categories/{category}', [UserCategoryController::class, 'show']);


// --- Authenticated User Routes ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', fn(Request $request) => $request->user());

    // Cart Routes
    Route::get('/cart', [CartController::class, 'show']);
    Route::post('/cart/items', [CartController::class, 'add']);
    Route::put('/cart/items/{cartItem}', [CartController::class, 'update']);
    Route::delete('/cart/items/{cartItem}', [CartController::class, 'remove']);

    // Checkout & Orders
    Route::post('/checkout', [CheckoutController::class, 'process']);
    Route::get('/orders', [UserOrderController::class, 'index']);
});

// --- Admin Routes ---
Route::middleware(['auth:sanctum', 'is_admin'])->prefix('admin')->group(function () {
    Route::apiResource('categories', AdminCategoryController::class);
    Route::apiResource('products', AdminProductController::class);
    Route::apiResource('orders', AdminOrderController::class);
});