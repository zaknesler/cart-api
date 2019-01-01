<?php

namespace App\Cart\Payments;

use App\Models\User;
use App\Models\PaymentMethod;

interface GatewayCustomer
{
    /**
     * Charge the customer a specific amount using a payment method.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @param  int  $amount
     * @return self
     */
    public function charge(PaymentMethod $paymentMethod, $amount);

    /**
     * Create and attach a card to a customer.
     *
     * @param string  $token
     */
    public function addCard($token);
}
