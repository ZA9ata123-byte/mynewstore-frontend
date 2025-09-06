<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException; // <-- 1. زدنا هادي لفوق
use Illuminate\Http\Request; // <-- 2. وزدنا حتى هادي

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // هنا فين كنزيدو الأسماء المستعارة ديال الـ Middleware
        $middleware->alias([
            'isAdmin' => \App\Http\Middleware\IsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // --- 3. هنا فين زدنا الكود الذكي اللي كيحل المشكل ---
        // هاد الكود كيقول لـ Laravel: إلا شي واحد ماشي مسجل الدخول وبغا يدخل لشي صفحة ديال API
        // رجع ليه رسالة خطأ JSON زوينة، ماشي تقلب على صفحة login اللي مكايناش
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Unauthenticated.'
                ], 401);
            }
        });
        // --- نهاية الإضافة ---

    })->create();
