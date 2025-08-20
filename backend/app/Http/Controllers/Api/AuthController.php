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

    // 5. الدالة ديال تسجيل دخول الأدمن
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // كنحاولو ندخلو المستخدم بالبيانات ديالو
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        // إلى دخَل، كنجيبو المعلومات ديالو
        $user = Auth::user();

        // أهم سطر: كنتأكدو واش هو أدمن
        if (!$user->is_admin) {
            // إلى ماشي أدمن، كنمحو ليه التوكن ونقولو ليه ممنوع الدخول
            $user->tokens()->delete();
            return response()->json(['message' => 'Forbidden: You are not an admin.'], 403);
        }

        // إلى كان أدمن، كنصاوبو ليه توكن خاص بالأدمن
        $token = $user->createToken('admin_auth_token')->plainTextToken;

        // كنرجعو ليه الجواب بالنجاح
        return response()->json([
            'message' => 'Admin successfully logged in',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}