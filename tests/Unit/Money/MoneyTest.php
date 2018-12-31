<?php

namespace Tests\Unit\Money;

use App\Cart\Money;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MoneyTest extends TestCase
{
    /** @test */
    function a_money_instance_can_return_the_raw_amount()
    {
        $money = new Money(1000);

        $this->assertEquals(1000, $money->amount());
    }

    /** @test */
    function a_money_instance_can_return_the_formatted_amount()
    {
        $money = new Money(1000);

        $this->assertEquals('$10.00', $money->formatted());
    }

    /** @test */
    function a_money_instance_can_add_up_the_value_from_another_money_instance()
    {
        $money = new Money(1000);

        $money = $money->add(new Money(1000));

        $this->assertEquals(2000, $money->amount());
    }

    /** @test */
    function a_money_instance_can_return_the_base_money_instance()
    {
        $money = new Money(1000);

        $this->assertInstanceOf(\Money\Money::class, $money->instance());
    }
}
