<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// --- المسارات الخاصة بمتجرنا ---

// -- مسارات الأقسام --
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category:slug}/products', [ProductController::class, 'productsByCategory']);


// -- مسارات المنتجات --
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);


// -- مسارات سلة التسوق --
Route::post('/cart', [CartController::class, 'store']); // <-- هذا هو المسار الجديد والمهم