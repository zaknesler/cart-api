<?php

namespace App\Cart\Payments\Stripe;

use Exception;
use App\Models\PaymentMethod;
use Stripe\Charge as StripeCharge;
use App\Cart\Payments\PaymentGateway;
use App\Cart\Payments\GatewayCustomer;
use Stripe\Customer as StripeCustomer;
use App\Exceptions\Payments\PaymentFailedException;
use App\Exceptions\PaymentMethods\PaymentMethodRemovalFailedException;

class StripeGatewayCustomer implements GatewayCustomer
{
    /**
     * The payment gateway to use.
     *
     * @var \App\Cart\Payments\PaymentGateway
     */
    protected $paymentGateway;

    /**
     * The Stripe customer.
     *
     * @var \Stripe\Customer
     */
    protected $customer;

    /**
     * Instantiate a new Stripe gateway customer.
     *
     * @param \App\Cart\Payments\PaymentGateway  $paymentGateway
     * @param \Stripe\Customer  $customer
     */
    public function __construct(PaymentGateway $paymentGateway, StripeCustomer $customer)
    {
        $this->paymentGateway = $paymentGateway;
        $this->customer = $customer;
    }

    /**
     * Charge the customer a specific amount using a payment method.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @param  int  $amount
     * @return self
     */
    public function charge(PaymentMethod $paymentMethod, int $amount)
    {
        try {
            StripeCharge::create([
                'currency' => strtolower(config('billing.currency')),
                'amount' => $amount,
                'customer' => $this->customer->id,
                'source' => $paymentMethod->provider_id,
            ]);
        } catch (Exception $e) {
            throw new PaymentFailedException;
        }
    }

    /**
     * Create and attach a card to a customer.
     *
     * @param  string  $token
     * @return \App\Models\PaymentMethod
     */
    public function addCard(string $token)
    {
        $card = $this->customer->sources->create([
            'source' => $token,
        ]);

        $this->customer->default_source = $card->id;
        $this->customer->save();

        return $this->paymentGateway->getUser()->paymentMethods()->create([
            'provider_id' => $card->id,
            'card_type' => $card->brand,
            'last_four' => $card->last4,
            'default' => true,
        ]);
    }

    /**
     * Remove a card from a customer.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return void
     */
    public function removeCard(PaymentMethod $paymentMethod)
    {
        try {
            $card = $this->customer->sources
                        ->retrieve($paymentMethod->provider_id)
                        ->delete();

            if (! $card->deleted) {
                throw new Exception;
            }
        } catch (Exception $e) {
            throw new PaymentMethodRemovalFailedException;
        }
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
