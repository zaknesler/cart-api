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
    public function charge(PaymentMethod $paymentMethod, int $amount);

    /**
     * Create and attach a card to a customer.
     *
     * @param  string  $token
     * @return \App\Models\PaymentMethod
     */
    public function addCard(string $token);

    /**
     * Remove a card from a customer.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return void
     */
    public function removeCard(PaymentMethod $paymentMethod);

    /**
     * Retrieve the customer's identifier from the provider.
     *
     * @return string
     */
    public function id();
}
