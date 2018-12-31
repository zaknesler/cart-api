<?php

namespace Tests\Unit\Models\Orders;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use App\Models\ShippingMethod;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function an_order_has_a_default_status_of_pending()
    {
        $order = factory(Order::class)->create();

        $this->assertEquals(Order::PENDING, $order->status);
    }

    /** @test */
    function an_order_belongs_to_a_user()
    {
        $user = factory(User::class)->create();
        $order = factory(Order::class)->create([
            'user_id' => 1,
        ]);

        $this->assertInstanceOf(User::class, $order->user);
    }

    /** @test */
    function an_order_belongs_to_a_shipping_method()
    {
        $shippingMethod = factory(ShippingMethod::class)->create();
        $order = factory(Order::class)->create([
            'shipping_method_id' => 1,
        ]);

        $this->assertInstanceOf(ShippingMethod::class, $order->shippingMethod);
    }

    /** @test */
    function an_order_belongs_to_an_address()
    {
        $address = factory(Address::class)->create();
        $order = factory(Order::class)->create([
            'address_id' => 1,
        ]);

        $this->assertInstanceOf(Address::class, $order->address);
    }
}
