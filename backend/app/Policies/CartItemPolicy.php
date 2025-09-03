<?php

namespace App\Policies;

use App\Models\CartItem;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CartItemPolicy
{
    /**
     * Rule: A user can update a cart item only if it belongs to their own cart.
     */
    public function update(User $user, CartItem $cartItem): bool
    {
        return $user->id === $cartItem->cart->user_id;
    }

    /**
     * Rule: A user can delete a cart item only if it belongs to their own cart.
     */
    public function delete(User $user, CartItem $cartItem): bool
    {
        return $user->id === $cartItem->cart->user_id;
    }
}