<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartService
{
    /**
     * Get the current user's or guest's cart.
     * Creates a new cart if one doesn't exist.
     */
    public function getCart(bool $create = true): ?Cart
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        }

        $cartToken = request()->cookie('cart_token');
        if ($cartToken) {
            return Cart::where('token', $cartToken)->first();
        }
        
        if ($create) {
            return Cart::create(['token' => Str::random(40)]);
        }

        return null;
    }

    /**
     * Add an item to the cart.
     */
    public function addItem(Cart $cart, array $data): Cart
    {
        $product = Product::findOrFail($data['product_id']);

        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $data['quantity']);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'price' => $product->price, // Or variant price
                'quantity' => $data['quantity'],
            ]);
        }

        return $cart->load('items.product');
    }

    /**
     * Update an item's quantity in the cart.
     */
    public function updateItem(CartItem $cartItem, int $quantity): Cart
    {
        $cartItem->update(['quantity' => $quantity]);
        return $cartItem->cart->load('items.product');
    }

    /**
     * Remove an item from the cart.
     */
    public function removeItem(CartItem $cartItem): Cart
    {
        $cart = $cartItem->cart;
        $cartItem->delete();
        return $cart->load('items.product');
    }
}