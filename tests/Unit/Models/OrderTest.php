<?php

namespace Tests\Unit\Models;

use App\Cart\Money;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use App\Models\Transaction;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use App\Models\ProductVariation;
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
    function an_order_belongs_to_a_payment_method()
    {
        $shippingMethod = factory(PaymentMethod::class)->create();
        $order = factory(Order::class)->create([
            'payment_method_id' => 1,
        ]);

        $this->assertInstanceOf(PaymentMethod::class, $order->paymentMethod);
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

    /** @test */
    function an_order_has_many_transactions()
    {
        $order = factory(Order::class)->create();

        factory(Transaction::class)->create([
            'order_id' => 1,
        ]);

        $this->assertInstanceOf(Transaction::class, $order->transactions->first());
    }

    /** @test */
    function an_order_has_many_product_variations()
    {
        $order = factory(Order::class)->create();

        $order->products()->attach(
            factory(ProductVariation::class)->create(), [
                'quantity' => 1,
            ]
        );

        $this->assertInstanceOf(ProductVariation::class, $order->products->first());
    }

    /** @test */
    function an_order_has_a_product_variation_with_a_quantity()
    {
        $order = factory(Order::class)->create();

        $order->products()->attach(
            factory(ProductVariation::class)->create(), [
                'quantity' => 3,
            ]
        );

        $this->assertEquals(3, $order->products->first()->pivot->quantity);
    }

    /** @test */
    function a_money_instance_is_returned_for_the_order_subtotal()
    {
        $order = factory(Order::class)->create();

        $this->assertInstanceOf(Money::class, $order->subtotal);
    }

    /** @test */
    function a_money_instance_is_returned_for_the_order_total()
    {
        $order = factory(Order::class)->create();

        $this->assertInstanceOf(Money::class, $order->total());
    }

    /** @test */
    function the_order_total_adds_shipping_cost_to_subtotal()
    {
        $shippingMethod = factory(ShippingMethod::class)->create([
            'price' => 500,
        ]);

        $order = factory(Order::class)->create([
            'subtotal' => 1000,
            'shipping_method_id' => $shippingMethod->id,
        ]);

        $this->assertEquals(1500, $order->total()->amount());
    }
}
