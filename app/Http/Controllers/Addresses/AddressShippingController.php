<?php

namespace App\Http\Controllers\Addresses;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ShippingMethodResource;

class AddressShippingController extends Controller
{
    /**
     * Create a new controller instance
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of shipping methods for an address.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Address $address)
    {
        $this->authorize('show', $address);

        return ShippingMethodResource::collection(
            $address->country->shippingMethods
        );
    }
}
