<?php

namespace App\Http\Controllers\Orders;

use Illuminate\Http\Request;
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
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function store(OrderStoreRequest $request)
    {
        //
    }
}
