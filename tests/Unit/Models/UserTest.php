<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use App\Models\PaymentMethod;
use App\Models\ProductVariation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_password_is_hashed_on_creation()
    {
        $user = factory(User::class)->create([
            'password' => 'secret-password',
        ]);

        $this->assertNotEquals('secret-password', $user->password);
    }

    /** @test */
    function a_user_has_many_cart_products()
    {
        $user = factory(User::class)->create();

        $user->cart()->attach(
            factory(ProductVariation::class)->create()
        );

        $this->assertInstanceOf(ProductVariation::class, $user->cart()->first());
    }

    /** @test */
    function a_users_cart_has_a_quantity_for_each_product()
    {
        $user = factory(User::class)->create();

        $user->cart()->attach(
            factory(ProductVariation::class)->create(), [
                'quantity' => 5,
            ]
        );

        $this->assertEquals(5, $user->cart->first()->pivot->quantity);
    }

    /** @test */
    function a_user_has_many_addresses()
    {
        $user = factory(User::class)->create();

        $user->addresses()->save(
            factory(Address::class)->create()
        );

        $this->assertInstanceOf(Address::class, $user->addresses()->first());
    }

    /** @test */
    function a_user_has_many_orders()
    {
        $user = factory(User::class)->create();

        $user->orders()->save(
            factory(Order::class)->create()
        );

        $this->assertInstanceOf(Order::class, $user->orders()->first());
    }

    /** @test */
    function a_user_has_many_payment_methods()
    {
        $user = factory(User::class)->create();

        $user->paymentMethods()->save(
            factory(PaymentMethod::class)->create()
        );

        $this->assertInstanceOf(PaymentMethod::class, $user->paymentMethods()->first());
    }
}
