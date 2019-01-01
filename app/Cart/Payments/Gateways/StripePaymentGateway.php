<?php

namespace App\Cart\Payments\Gateways;

use App\Models\User;
use App\Cart\Payments\PaymentGateway;

class StripePaymentGateway implements PaymentGateway
{
    /**
     * The user to target.
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * Target a specific user for payment handling.
     *
     * @param  \App\Models\User  $user
     * @return self
     */
    public function withUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Create the customer on the provider's end.
     *
     * @return \App\Cart\Payments\GatewayCustomer
     */
    public function createCustomer()
    {
        return new StripeGatewayCustomer();
    }
}
