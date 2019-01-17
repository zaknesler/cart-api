<?php

namespace App\Http\Controllers\PaymentMethods;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Cart\Payments\PaymentGateway;
use App\Http\Resources\PaymentMethodResource;
use App\Events\PaymentMethods\PaymentMethodDeleted;
use App\Http\Requests\PaymentMethods\StorePaymentMethodRequest;

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
     * @param  \App\Http\Requests\PaymentMethods\StorePaymentMethodRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePaymentMethodRequest $request)
    {
        $card = $this->paymentGateway
                ->withUser($request->user())
                ->createCustomer()
                ->addCard($request->token);

        return new PaymentMethodResource($card);
    }

    /**
     * Remove a specified payment method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return void
     */
    public function destroy(Request $request, PaymentMethod $paymentMethod)
    {
        $this->authorize('delete', $paymentMethod);

        $paymentMethod->delete();

        event(new PaymentMethodDeleted($paymentMethod));

        return PaymentMethodResource::collection(
            $request->user()->paymentMethods
        );
    }
}
