<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\OrderController; // <-- زدنا هادا

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// =========================================================================
// == Public Routes (Routes for Guests and All Users)
// =========================================================================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/cart/add', [CartController::class, 'addToCart']);
// Route::post('/login', [AuthController::class, 'login']);


// =========================================================================
// == Protected User Routes (Routes for Logged-in Users)
// =========================================================================
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/checkout', [CheckoutController::class, 'placeOrder']);
    // Here we can add other routes like viewing user's own orders, etc.
});


// =========================================================================
// == Admin Routes
// =========================================================================
Route::post('/admin/login', [AuthController::class, 'adminLogin']);

// --- Protected Admin Routes ---
// Routes below are protected by two guards:
// 1. 'auth:sanctum' -> Ensures the user is logged in.
// 2. 'admin' -> Our custom middleware that ensures the user has is_admin = true.
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // --- Product Management ---
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);

    // --- Category Management ---
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
    
    // --- User Management ---
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);

    // --- Order Management ---
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::put('/orders/{order}', [OrderController::class, 'update']);
    
});