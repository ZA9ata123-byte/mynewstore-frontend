<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    private function getOrCreateCart(Request $request)
    {
        $guestToken = $request->header('X-Cart-Token');
        $user = Auth::guard('sanctum')->user();

        // If user is logged in
        if ($user) {
            // Find or create a cart for the logged-in user
            $userCart = Cart::firstOrCreate(
                ['user_id' => $user->id]
            );

            // If a guest cart token is also present, we need to merge
            if ($guestToken) {
                $guestCart = Cart::where('token', $guestToken)->whereNull('user_id')->first();

                if ($guestCart && $guestCart->id !== $userCart->id) {
                    // Move items from guest cart to user cart
                    foreach ($guestCart->items as $guestItem) {
                        $existingItem = $userCart->items()->where('product_id', $guestItem->product_id)->first();

                        if ($existingItem) {
                            // If item already exists, just update quantity
                            $existingItem->quantity += $guestItem->quantity;
                            $existingItem->save();
                        } else {
                            // If not, move the item by changing its cart_id
                            $guestItem->cart_id = $userCart->id;
                            $guestItem->save();
                        }
                    }
                    // After moving all items, the user cart must be reloaded
                    $userCart->load('items');
                    // Delete the now-empty guest cart
                    $guestCart->delete();
                }
            }
            return $userCart;
        }

        // If user is a guest
        if ($guestToken) {
            return Cart::firstOrCreate(['token' => $guestToken]);
        }

        // If no token, create a brand new guest cart
        return Cart::create(['token' => Str::random(40)]);
    }


    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->getOrCreateCart($request);
        $product = Product::findOrFail($request->product_id);
        $quantity = $request->input('quantity', 1);

        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        $user = Auth::guard('sanctum')->user();
        return response()->json([
            'message' => 'Product added to cart successfully!',
            'cart_token' => $user ? null : $cart->token,
            'cart' => $cart->load('items.product')
        ], 200);
    }
}