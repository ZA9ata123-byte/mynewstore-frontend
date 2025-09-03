<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cart\AddCartItemRequest;
use App\Http\Requests\Api\Cart\UpdateCartItemRequest;
use App\Models\CartItem;
use App\Models\User; // ✅ استيراد المودل
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService)
    {
    }

    public function show()
    {
        $cart = $this->cartService->getCart(false);

        if (!$cart) {
            return response()->json(['items' => [], 'total' => 0]);
        }

        $cookie = $cart->token ? cookie('cart_token', $cart->token, 60 * 24 * 30) : null;
        $response = response()->json($cart->load('items.product'));

        return $cookie ? $response->withCookie($cookie) : $response;
    }

    public function add(AddCartItemRequest $request)
    {
        $cart = $this->cartService->getCart(true);
        $updatedCart = $this->cartService->addItem($cart, $request->validated());

        $cookie = $cart->token ? cookie('cart_token', $cart->token, 60 * 24 * 30) : null;
        $response = response()->json($updatedCart, 201);

        return $cookie ? $response->withCookie($cookie) : $response;
    }

    public function update(UpdateCartItemRequest $request, CartItem $cartItem)
    {
        /** @var User $user */
        $user = Auth::user();

        // ✅ استخدام الطريقة الجديدة، الواضحة والاحترافية
        if (!$user || !$user->ownsCartItem($cartItem)) {
            abort(403, 'This action is unauthorized.');
        }

        $updatedCart = $this->cartService->updateItem($cartItem, $request->validated()['quantity']);
        return response()->json($updatedCart);
    }

    public function remove(CartItem $cartItem)
    {
        /** @var User $user */
        $user = Auth::user();
        
        // ✅ استخدام الطريقة الجديدة، الواضحة والاحترافية
        if (!$user || !$user->ownsCartItem($cartItem)) {
            abort(403, 'This action is unauthorized.');
        }

        $updatedCart = $this->cartService->removeItem($cartItem);
        return response()->json($updatedCart);
    }
}