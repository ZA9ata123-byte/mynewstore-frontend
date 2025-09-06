<?php

namespace App\Services;

// ✅ استدعاء المودل ديال المستخدم، ضروري
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * ✅✅✅ هادي هي الميثود لي كانت ناقصة ✅✅✅
     * Register a new user.
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function registerUser(array $data)
    {
        // كنصايبو مستخدم جديد وكنقومو بتشفير كلمة السر
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Attempt to log the user in.
     *
     * @param array $credentials
     * @return string The new API token.
     * @throws ValidationException
     */
    public function loginUser(array $credentials): string
    {
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials do not match our records.'],
            ]);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // كنحيدو أي توكن قديم عندو باش منخليوش توكنات بزاف
        $user->tokens()->delete();

        // كنصايبو ليه توكن جديد
        return $user->createToken('auth_token')->plainTextToken;
    }
}