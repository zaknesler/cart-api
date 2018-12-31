<?php

namespace App\Http\Controllers\Addresses;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AddressResource;

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
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function index(Request $request)
    {
        return AddressResource::collection(
            $request->user()->addresses
        );
    }
}
