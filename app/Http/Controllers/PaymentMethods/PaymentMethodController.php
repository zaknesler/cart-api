<?php

namespace App\Http\Controllers\PaymentMethods;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cart\Payments\PaymentGateway;
use App\Http\Resources\PaymentMethodResource;

class PaymentMethodController extends Controller
{
    /**
     * An implementation of the payment gateway.
     *
     * @var \App\Cart\Payments\PaymentGateway
     */
    protected $paymentGateway;

    /**
     * Create a new controller instance
     */
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->middleware('auth:api');

        $this->paymentGateway = $paymentGateway;
    }

    /**
     * Get a list of the user's payment methods
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return PaymentMethodResource::collection(
            $request->user()->paymentMethods
        );
    }

    /**
     * Store a new payment method for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $card = $this->paymentGateway->withUser($request->user())
            ->createCustomer()
            ->addCard($request->token);
    }
}
