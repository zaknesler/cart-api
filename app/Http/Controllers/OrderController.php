<?php

namespace App\Http\Controllers;

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
        $this->middleware(['cart.sync', 'cart.checkIsEmpty'])->only('store');
    }

    /**
     * Get an index of a user's orders.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $orders = $request->user()->orders()
            ->with([
                'products.stock',
                'products.type',
                'products.product.variations.stock',
                'address.country',
                'address.countryDivision',
                'shippingMethod',
                'paymentMethod',
            ])->latest()->paginate(10);

        return OrderResource::collection($orders);
    }

    /**
     * Store an order for the user.
     *
     * @param  \App\Http\Requests\Orders\OrderStoreRequest  $request
     * @param  \App\Cart\Cart  $cart
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(OrderStoreRequest $request, Cart $cart)
    {
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
