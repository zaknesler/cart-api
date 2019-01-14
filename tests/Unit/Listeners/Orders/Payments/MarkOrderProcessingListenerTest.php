<?php

namespace Tests\Unit\Listeners\Orders\Payments;

use Tests\TestCase;
use App\Models\Order;
use App\Events\Orders\OrderPaid;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Listeners\Orders\Payments\MarkOrderProcessing;

class MarkOrderProcessingListenerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function the_mark_order_processing_listener_should_mark_an_order_as_processing()
    {
        $event = new OrderPaid(
            $order = factory(Order::class)->create()
        );

        $listener = new MarkOrderProcessing();
        $listener->handle($event);

        $this->assertEquals(Order::PROCESSING, $order->fresh()->status);
    }
}
