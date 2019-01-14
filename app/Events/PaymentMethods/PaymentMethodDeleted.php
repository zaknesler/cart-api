<?php

namespace App\Events\PaymentMethods;

use App\Models\PaymentMethod;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class PaymentMethodDeleted
{
    use Dispatchable, SerializesModels;

    /**
     * The payment method that was deleted.
     *
     * @var \App\Models\PaymentMethod
     */
    public $paymentMethod;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return void
     */
    public function __construct(PaymentMethod $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }
}
