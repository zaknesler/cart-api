<?php

namespace App\Listners\Orders;

use App\Cart\Cart;
use App\Events\Orders\OrderCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
     * @param  OrderCreated  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $this->cart->empty();
    }
}
