<?php

namespace App\Cart\Payments\Stripe;

use App\Models\User;
use App\Cart\Payments\PaymentGateway;
use Stripe\Customer as StripeCustomer;

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
        if ($this->user->gateway_customer_id) {
            return $this->getCustomer();
        }

        $customer = new StripeGatewayCustomer(
            $this,
            $this->createStripeCustomer()
        );

        $this->user->update([
            'gateway_customer_id' => $customer->id(),
        ]);

        return $customer;
    }

    /**
     * Get the user attached to the gateway.
     *
     * @return \App\Models\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Fetch a customer from the provider based on the current user.
     *
     * @return \App\Cart\Payments\GatewayCustomer
     */
    public function getCustomer()
    {
        return new StripeGatewayCustomer(
            $this,
            StripeCustomer::retrieve($this->user->gateway_customer_id)
        );
    }

    /**
     * Create a customer on the provider's end.
     *
     * @return \Stripe\Customer
     */
    private function createStripeCustomer()
    {
        return StripeCustomer::create([
            'email' => $this->user->email,
        ]);
    }
}
