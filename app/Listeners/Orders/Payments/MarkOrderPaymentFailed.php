<?php

namespace App\Listeners\Orders\Payments;

use App\Models\Order;

class MarkOrderPaymentFailed
{
    /**
     * Handle the event.
     *
     * @param  mixed  $event
     * @return void
     */
    public function handle($event)
    {
        $event->order->update([
            'status' => Order::PAYMENT_FAILED,
        ]);
    }
}
