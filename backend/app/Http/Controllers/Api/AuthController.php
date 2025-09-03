<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest; // ✅ استخدام الـ Request الجديد
use App\Services\AuthService; // ✅ استخدام الـ Service الجديد
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService)
    {
    }

    /**
     * Register a new user.
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->authService->registerUser($request->validated());
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 201);
    }

    /**
     * Login user and create token.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $token = $this->authService->loginUser($credentials);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => Auth::user(),
        ]);
    }

    /**
     * Logout user (revoke the token).
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}