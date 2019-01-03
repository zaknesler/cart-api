<?php

namespace App\Http\Controllers\Addresses;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AddressResource;
use App\Http\Requests\Addresses\AddressStoreRequest;

class AddressController extends Controller
{
    /**
     * Create a new controller instance
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Get a list of the user's addresses.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return AddressResource::collection(
            $request->user()->addresses->load('countryDivision.type')
        );
    }

    /**
     * Store a new address for a user.
     *
     * @param  \App\Http\Requests\Addresses\AddressStoreRequest  $request
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function store(AddressStoreRequest $request)
    {
        $address = $request->user()
            ->addresses()
            ->create($request->validated());

        return new AddressResource($address);
    }
}
