<?php

namespace App\Cart\Payments\Gateways;

use App\Models\PaymentMethod;
use App\Cart\Payments\GatewayCustomer;

class StripeGatewayCustomer implements GatewayCustomer
{
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
     * @param string  $token
     */
    public function addCard($token)
    {
        //
    }
}
