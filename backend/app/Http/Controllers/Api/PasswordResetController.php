<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Mail\PasswordResetMail; // غادي نصاوبوها فالخطوة الجاية

class PasswordResetController extends Controller
{
    /**
     * Handle user request to get a password reset link.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // كنصاوبو توكن سري ومؤقت
        $token = Str::random(60);

        // كنسجلوه ف قاعدة البيانات مع الإيميل
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        // --- ملاحظة مهمة جدا ---
        // هاد الرابط خاصو يكون ديال الفرونت-اند ديالك، ماشي الباك-اند
        $resetLink = 'http://localhost:3000/reset-password?token=' . $token . '&email=' . urlencode($request->email);

        // كنصيفطو الإيميل للمستخدم
        Mail::to($request->email)->send(new PasswordResetMail($resetLink));

        return response()->json(['message' => 'Password reset link sent successfully.'], 200);
    }

    /**
     * Handle the actual password reset.
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // كنتأكدو واش التوكن صحيح ومزال صالح
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetRecord || Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            return response()->json(['message' => 'Invalid or expired token.'], 400);
        }

        // كنجيبو المستخدم وكنديرو ليه كلمة سر جديدة
        $user = User::where('email', $request->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        // كنمحو التوكن باش مايتستعملش مرة أخرى
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Password has been successfully reset.'], 200);
    }
}