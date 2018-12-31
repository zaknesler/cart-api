<?php

namespace App\Http\Controllers\Cart;

use App\Cart\Cart;
use Illuminate\Http\Request;
use App\Models\ProductVariation;
use App\Http\Controllers\Controller;
use App\Http\Resources\Cart\CartResource;
use App\Http\Requests\Cart\CartIndexRequest;
use App\Http\Requests\Cart\CartStoreRequest;
use App\Http\Requests\Cart\CartUpdateRequest;

class CartController extends Controller
{
    /**
     * Create a new controller instance
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Fetch all of the products in the user's cart.
     *
     * @param  \App\Http\Requests\Cart\CartIndexRequest  $request
     * @param  \App\Cart\Cart  $cart
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function index(CartIndexRequest $request, Cart $cart)
    {
        $cart->sync();

        $request->user()->load([
            'cart.product',
            'cart.product.variations.stock',
        ]);

        return (new CartResource($request->user()))
            ->additional(['meta' => [
                'empty' => $cart->isEmpty(),
                'subtotal' => $cart->subtotal()->formatted(),
                'total' => $cart->withShippingMethod($request->shipping_method_id)
                                ->total()
                                ->formatted(),
                'changed' => $cart->hasChanged(),
            ]]);
    }

    /**
     * Save items to the user's cart.
     *
     * @param  \App\Http\Requests\Cart\CartStoreRequest  $request
     * @param  \App\Cart\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function store(CartStoreRequest $request, Cart $cart)
    {
        $cart->add($request->products);
    }

    /**
     * Update a product in the user's cart.
     *
     * @param  \App\Models\ProductVariation  $productVariation
     * @param  \App\Http\Requests\Cart\CartUpdateRequest  $request
     * @param  \App\Cart\Cart  $cart
     * @return void
     */
    public function update(ProductVariation $productVariation, CartUpdateRequest $request, Cart $cart)
    {
        $cart->update($productVariation->id, $request->quantity);
    }

    /**
     * Remove a product from the user's cart.
     *
     * @param  \App\Models\ProductVariation  $productVariation
     * @param  \App\Cart\Cart  $cart
     * @return void
     */
    public function destroy(ProductVariation $productVariation, Cart $cart)
    {
        $cart->delete($productVariation->id);
    }
}
