<?php

namespace App\Listeners\Orders;

use App\Events\Orders\OrderPaid;
use App\Events\Orders\OrderCreated;
use App\Cart\Payments\PaymentGateway;
use App\Events\Orders\OrderPaymentFailed;
use App\Exceptions\Payments\PaymentFailedException;

class ProcessPayment
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
     * @param  \App\Events\Orders\OrderCreated  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        try {
            $this->paymentGateway
                ->withUser($event->order->user)
                ->createCustomer()
                ->charge(
                    $event->order->paymentMethod,
                    $event->order->total()->amount()
                );

            event(new OrderPaid($event->order));
        } catch (PaymentFailedException $e) {
            event(new OrderPaymentFailed($event->order));
        }
    }
}
