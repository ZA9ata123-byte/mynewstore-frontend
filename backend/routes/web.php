<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;

// 1. مسار تسجيل الدخول (الآن في المكان الصحيح)
Route::post('/login', [AuthController::class, 'login']);

// 2. مسار تسجيل الخروج
Route::post('/logout', [AuthController::class, 'logout']);

// 3. مسارات السلة (لأنها تتطلب جلسة آمنة)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::put('/cart/{product_variant_id}', [CartController::class, 'update']);
    Route::delete('/cart/{product_variant_id}', [CartController::class, 'destroy']);
});