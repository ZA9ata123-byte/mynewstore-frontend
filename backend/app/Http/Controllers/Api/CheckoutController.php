<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    public function placeOrder(Request $request)
    {
        $user = Auth::user();
        $guestToken = $request->header('X-Cart-Token');

        // --- START: The Magic Merge Logic ---
        $userCart = Cart::firstOrCreate(['user_id' => $user->id]);

        if ($guestToken) {
            $guestCart = Cart::where('token', $guestToken)->whereNull('user_id')->first();
            if ($guestCart) {
                foreach ($guestCart->items as $guestItem) {
                    $existingItem = $userCart->items()->where('product_id', $guestItem->product_id)->first();
                    if ($existingItem) {
                        $existingItem->quantity += $guestItem->quantity;
                        $existingItem->save();
                    } else {
                        $guestItem->cart_id = $userCart->id;
                        $guestItem->save();
                    }
                }
                $userCart->load('items');
                $guestCart->delete();
            }
        }
        // --- END: The Magic Merge Logic ---

        $cart = $userCart; // Now we are sure we have the correct merged cart

        if ($cart->items->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty.'], 400);
        }

        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'billing_address' => 'required|string|max:255',
        ]);

        $totalAmount = $cart->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'shipping_address' => $request->shipping_address,
            'billing_address' => $request->billing_address,
        ]);

        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        $cart->delete();

        return response()->json([
            'message' => 'Order placed successfully!',
            'order' => $order->load('items.product')
        ], 201);
    }
}