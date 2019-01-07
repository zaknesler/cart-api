<?php

namespace App\Cart\Payments;

use App\Models\User;

interface PaymentGateway
{
    /**
     * Target a specific user for payment handling.
     *
     * @param  \App\Models\User  $user
     * @return self
     */
    public function withUser(User $user);

    /**
     * Create the customer on the provider's end.
     *
     * @return self
     */
    public function createCustomer();

    /**
     * Get the user attached to the gateway.
     *
     * @return \App\Models\User
     */
    public function getUser();

    /**
     * Fetch a customer from the provider based on the current user.
     *
     * @return \App\Cart\Payments\GatewayCustomer
     */
    public function getCustomer();
}
