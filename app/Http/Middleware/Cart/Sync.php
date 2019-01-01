<?php

namespace App\Http\Middleware\Cart;

use Closure;
use App\Cart\Cart;

class Sync
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
        $this->cart->sync();

        if ($this->cart->hasChanged()) {
            return response()->json([
                'message' => 'Oh no! Some items in your cart have changed. Please review these changes before placing your order.',
            ], 409);
        }

        return $next($request);
    }
}
