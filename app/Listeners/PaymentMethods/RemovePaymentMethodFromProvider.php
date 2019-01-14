<?php

namespace App\Listeners\PaymentMethods;

use App\Cart\Payments\PaymentGateway;
use App\Events\PaymentMethods\PaymentMethodDeleted;
use App\Exceptions\PaymentMethods\PaymentMethodRemovalFailedException;

class RemovePaymentMethodFromProvider
{
    /**
     * The payment gateway implementation.
     *
     * @var \App\Cart\Payments\PaymentGateway
     */
    protected $paymentGateway;

    /**
     * Create the event listener.
     *
     * @param  \App\Cart\Payments\PaymentGateway  $paymentGateway
     * @return void
     */
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\PaymentMethods\PaymentMethodDeleted  $event
     * @return void
     */
    public function handle(PaymentMethodDeleted $event)
    {
        try {
            $this->paymentGateway
                ->withUser($event->paymentMethod->user)
                ->getCustomer()
                ->removeCard($event->paymentMethod);
        } catch (PaymentMethodRemovalFailedException $e) {
            //
        }
    }
}
