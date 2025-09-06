<?php

// ✅ تأكد أن العنوان (namespace) صحيح وفيه Admin
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Handle an admin login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'المعلومات غير صحيحة'], 401);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->is_admin) {
            Auth::logout();
            return response()->json([
                'message' => 'ليس لديك صلاحيات الأدمن'
            ], 403);
        }

        $token = $user->createToken('admin_auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
            'is_admin' => $user->is_admin
        ]);
    }
}