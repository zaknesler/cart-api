<?php

namespace App\Listeners\Orders\Payments;

use App\Models\Order;

class MarkOrderProcessing
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
            'status' => Order::PROCESSING,
        ]);
    }
}
