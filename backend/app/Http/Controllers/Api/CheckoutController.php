<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator; // <-- إضافة مهمة للتحقق اليدوي
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function placeOrder(Request $request)
    {
        // --- التصحيح 1: التحقق من صحة البيانات (Validation) أصبح أكثر دقة ---
        $validator = Validator::make($request->all(), [
            'shipping_info.name' => 'required|string|max:255',
            'shipping_info.email' => 'required|email|max:255',
            'shipping_info.address' => 'required|string|max:255',
            'shipping_info.city' => 'required|string|max:255',
            'shipping_info.phone' => 'required|string|max:20',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'create_account' => 'boolean',
            // كلمة المرور مطلوبة فقط إذا كان خيار إنشاء حساب محدداً
            'password' => 'required_if:create_account,true|nullable|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'البيانات المدخلة غير صالحة', 'errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        return DB::transaction(function () use ($validatedData, $request) {
            // --- التصحيح 2: التعامل مع المستخدم بشكل آمن ---
            $user = auth('sanctum')->user(); // نحاول الحصول على المستخدم المسجل دخوله
            $shippingInfo = $validatedData['shipping_info'];

            if (!$user && $request->create_account) {
                if (User::where('email', $shippingInfo['email'])->exists()) {
                    return response()->json(['message' => 'هذا البريد الإلكتروني مستخدم بالفعل.'], 422);
                }
                $user = User::create([
                    'name' => $shippingInfo['name'],
                    'email' => $shippingInfo['email'],
                    'password' => Hash::make($validatedData['password']),
                ]);
            }

            $order = Order::create([
                'user_id' => $user ? $user->id : null, // نربط الطلب بالمستخدم إذا كان موجوداً
                'status' => 'pending',
                'total_price' => $validatedData['total_price'],
                'shipping_info' => json_encode($shippingInfo),
            ]);

            foreach ($validatedData['items'] as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            return response()->json([
                'message' => 'تم استلام طلبك بنجاح!',
                'order' => $order->load('items'),
            ], 201);
        });
    }
}