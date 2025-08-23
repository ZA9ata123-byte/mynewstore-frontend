<?php

// 1. العنوان: كنقولو لارافيل هاد الملف فين كاين
namespace App\Http\Controllers\Api;

// 2. الاستدعاءات: كنجيبو الأدوات اللي غادي نحتاجو
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// 3. تعريف الكلاس: خاص السمية تكون مطابقة لسمية الملف
class AuthController extends Controller
{
    // 4. الدالة ديال التسجيل (هادي ديجا خدامة عندك مزيان)
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    // --- هادي هي الإضافة الأولى ---
    /**
     * Login for regular users.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        // كنمحو أي توكن قديم عند المستخدم باش نضمنو عندو جلسة (session) وحدة مفتوحة
        $user->tokens()->delete();

        // كنصاوبو ليه توكن جديد
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    // --- هادي هي الإضافة الثانية ---
    /**
     * Logout user (Revoke the token).
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }


    // 5. الدالة ديال تسجيل دخول الأدمن (بقات كيفما هي)
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $user = Auth::user();

        if (!$user->is_admin) {
            $user->tokens()->delete();
            return response()->json(['message' => 'Forbidden: You are not an admin.'], 403);
        }

        $token = $user->createToken('admin_auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Admin successfully logged in',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}