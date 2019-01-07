<?php

namespace Tests\Unit\Listeners;

use Tests\TestCase;
use App\Models\Order;
use App\Events\Orders\OrderPaymentFailed;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Listeners\Orders\Payments\MarkOrderPaymentFailed;

class MarkOrderPaymentFailedListenerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function the_mark_order_payment_failed_listener_should_mark_an_order_as_payment_failed()
    {
        $event = new OrderPaymentFailed(
            $order = factory(Order::class)->create()
        );

        $listener = new MarkOrderPaymentFailed();
        $listener->handle($event);

        $this->assertEquals(Order::PAYMENT_FAILED, $order->fresh()->status);
    }
}
