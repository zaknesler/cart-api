<?php

namespace Tests\Unit\Models\ShippingMethods;

use App\Cart\Money;
use Tests\TestCase;
use App\Models\Country;
use App\Models\ShippingMethod;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShippingMethodTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_shipping_method_returns_a_money_instance_for_the_price()
    {
        $shippingMethod = factory(ShippingMethod::class)->create();

        $this->assertInstanceOf(Money::class, $shippingMethod->price);
    }

    /** @test */
    function a_shipping_method_can_return_a_formatted_price()
    {
        $shippingMethod = factory(ShippingMethod::class)->create([
            'price' => 500,
        ]);

        $this->assertEquals('$5.00', $shippingMethod->price->formatted());
    }

    /** @test */
    function a_shipping_method_belongs_to_many_countries()
    {
        $shippingMethod = factory(ShippingMethod::class)->create();

        $shippingMethod->countries()->attach(
            factory(Country::class)->create()
        );

        $this->assertInstanceOf(Country::class, $shippingMethod->countries()->first());
    }
}
