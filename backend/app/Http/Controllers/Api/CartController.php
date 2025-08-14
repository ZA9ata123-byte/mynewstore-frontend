<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    /**
     * Add an item to the cart.
     */
    public function store(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // For now, we'll use a session ID to manage the cart.
        // Later, we can link this to the logged-in user.
        $sessionId = $request->session()->getId();
        if (!$sessionId) {
            $sessionId = Str::uuid()->toString();
            $request->session()->put('cart_session_id', $sessionId);
        }

        $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        $variant = ProductVariant::find($request->variant_id);

        // Check if this variant is already in the cart
        $cartItem = $cart->items()->where('product_variant_id', $request->variant_id)->first();

        if ($cartItem) {
            // If it exists, just update the quantity
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // If it's new, create a new cart item
            $cart->items()->create([
                'product_id' => $variant->product_id,
                'product_variant_id' => $request->variant_id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json(['message' => 'Product added to cart successfully!']);
    }
}