<?php

namespace App\Listeners\Orders;

use App\Cart\Cart;
use App\Events\Orders\OrderCreated;

class EmptyCart
{
    /**
     * The instance of the user's cart.
     *
     * @var \App\Cart\Cart
     */
    protected $cart;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
        $this->cart->empty();
    }
}
