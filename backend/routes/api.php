<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\OrderController;

// --- Public Routes ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']); // <-- تمت إضافة هادي
Route::post('/admin/login', [AuthController::class, 'adminLogin']);
Route::post('/cart/add', [CartController::class, 'addToCart']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

// --- Protected User Routes ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']); // <-- تمت إضافة هادي
    Route::post('/checkout', [CheckoutController::class, 'placeOrder']);
});

// --- Admin Routes ---
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());
    // استعملنا apiResource هنا باش نجمعو الروابط ديال الأدمن
    Route::apiResource('products', AdminProductController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('users', UserController::class)->except(['store', 'update']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{user}/assign-role', [UserController::class, 'assignRole']); // <-- تمت إضافة هادي
    Route::apiResource('orders', OrderController::class)->only(['index', 'show', 'update']);
});