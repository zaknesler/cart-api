<?php

namespace App\Listeners\Orders\Payments;

use App\Events\Orders\OrderPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateTransaction
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\Orders\OrderPaid  $event
     * @return void
     */
    public function handle(OrderPaid $event)
    {
        $event->order->transactions()->create([
            'total' => $event->order->total()->amount(),
        ]);
    }
}
