<?php

namespace App\Cart\Payments\Gateways;

use App\Models\PaymentMethod;
use App\Cart\Payments\PaymentGateway;
use App\Cart\Payments\GatewayCustomer;
use Stripe\Customer as StripeCustomer;

class StripeGatewayCustomer implements GatewayCustomer
{
    /**
     * The payment gateway to use.
     *
     * @var \App\Cart\Payments\PaymentGateway
     */
    protected $gateway;

    /**
     * The Stripe customer.
     *
     * @var \Stripe\Customer
     */
    protected $customer;

    /**
     * Instantiate a new Stripe gateway customer.
     *
     * @param \App\Cart\Payments\PaymentGateway  $gateway
     * @param \Stripe\Customer  $customer
     */
    public function __construct(PaymentGateway $gateway, StripeCustomer $customer)
    {
        $this->gateway = $gateway;

        $this->customer = $customer;
    }


    /**
     * Charge the customer a specific amount using a payment method.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @param  int  $amount
     * @return self
     */
    public function charge(PaymentMethod $paymentMethod, $amount)
    {
        //
    }

    /**
     * Create and attach a card to a customer.
     *
     * @param  string  $token
     * @return \App\Models\PaymentMethod
     */
    public function addCard($token)
    {
        $card = $this->customer->sources->create([
            'source' => $token,
        ]);

        $this->customer->default_source = $card->id;
        $this->customer->save();

        return $this->gateway->user()->paymentMethods()->create([
            'provider_id' => $card->id,
            'card_type' => $card->brand,
            'last_four' => $card->last4,
            'default' => true,
        ]);
    }

    /**
     * Retrieve the customer's identifier from the provider.
     *
     * @return string
     */
    public function id()
    {
        return $this->customer->id;
    }
}
