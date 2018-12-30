<?php

namespace App\Http\Controllers\Cart;

use App\Cart\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\CartStoreRequest;

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
     * Save items to the user's cart.
     *
     * @param  \App\Http\Requests\Cart\CartStoreRequest  $request
     * @param  \App\Cart\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function store(CartStoreRequest $request, Cart $cart)
    {
        $cart->addProducts($request->products);
    }
}
