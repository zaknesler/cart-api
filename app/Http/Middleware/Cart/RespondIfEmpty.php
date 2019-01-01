<?php

namespace App\Http\Middleware\Cart;

use Closure;
use App\Cart\Cart;

class RespondIfEmpty
{
    /**
     * The user's cart.
     *
     * @var \App\Cart\Cart
     */
    protected $cart;

    /**
     * Instantiate a new middleware class.
     *
     * @param \App\Cart\Cart  $cart
     */
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->cart->isEmpty()) {
            return response()->json([
                'Your cart is empty',
            ], 400);
        }

        return $next($request);
    }
}
