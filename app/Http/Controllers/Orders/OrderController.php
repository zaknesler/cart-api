<?php

namespace App\Http\Controllers\Orders;

use App\Cart\Cart;
use Illuminate\Http\Request;
use App\Models\ProductVariation;
use App\Http\Controllers\Controller;
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
        $order = $request->user()->orders()->create(
            array_merge($request->validated(), [
                'subtotal' => $cart->subtotal()->amount(),
            ])
        );

        $products = $cart->products()
            ->keyBy('id')
            ->map(function ($product) {
                return [
                    'quantity' => $product->pivot->quantity,
                ];
            });

        $order->products()->sync($products);
    }
}
