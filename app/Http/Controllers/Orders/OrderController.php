<?php

namespace App\Http\Controllers\Orders;

use App\Cart\Cart;
use Illuminate\Http\Request;
use App\Models\ProductVariation;
use App\Events\Orders\OrderCreated;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Requests\Orders\OrderStoreRequest;

class OrderController extends Controller
{
    /**
     * Create a new controller instance
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Store an order for the user.
     *
     * @param  \App\Http\Requests\Orders\OrderStoreRequest  $request
     * @param  \App\Cart\Cart  $cart
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function store(OrderStoreRequest $request, Cart $cart)
    {
        if ($cart->isEmpty()) {
            abort(400, 'Your cart is empty.');
        }

        $order = $this->createOrder($request, $cart);
        $order->products()->sync($cart->products()->forSyncing());

        event(new OrderCreated($order));

        return new OrderResource($order);
    }

    /**
     * Create the order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cart\Cart  $cart
     * @return \App\Models\Order
     */
    private function createOrder(Request $request, Cart $cart)
    {
        return $request->user()->orders()->create(
            array_merge($request->validated(), [
                'subtotal' => $cart->subtotal()->amount(),
            ])
        );
    }
}
