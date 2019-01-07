<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_transaction_belongs_to_an_order()
    {
        $order = factory(Order::class)->create();

        $transaction = factory(Transaction::class)->create([
            'order_id' => 1,
        ]);

        $this->assertInstanceOf(Order::class, $transaction->order);
    }
}
