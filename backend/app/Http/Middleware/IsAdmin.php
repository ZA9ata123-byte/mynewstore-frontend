<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // ✅ استيراد Auth
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ✅ نتأكد أولاً أن المستخدم مسجل الدخول، عاد نتأكد واش هو أدمن
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized. You do not have admin privileges.'], 403);
    }
}