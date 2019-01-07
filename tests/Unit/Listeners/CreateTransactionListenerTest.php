<?php

namespace Tests\Unit\Listeners;

use Tests\TestCase;
use App\Models\Order;
use App\Events\Orders\OrderPaid;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Listeners\Orders\Payments\CreateTransaction;


class CreateTransactionListenerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function the_create_transaction_listener_creates_a_transaction_after_an_order_is_paid()
    {
        $event = new OrderPaid(
            $order = factory(Order::class)->create()
        );

        $listener = new CreateTransaction;
        $listener->handle($event);

        $this->assertDatabaseHas('transactions', [
            'order_id' => 1,
            'total' => $order->total()->amount(),
        ]);
    }
}
