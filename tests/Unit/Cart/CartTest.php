<?php

namespace Tests\Unit\Cart;

use App\Cart\Cart;
use App\Cart\Money;
use Tests\TestCase;
use App\Models\User;
use App\Models\ProductVariation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function products_can_be_added_to_the_cart()
    {
        $cart = new Cart($user = factory(User::class)->create());
        $product = factory(ProductVariation::class)->create();

        $cart->add([
            ['id' => 1, 'quantity' => 1],
        ]);

        $this->assertCount(1, $user->fresh()->cart);
    }

    /** @test */
    function quantity_of_existing_products_in_the_cart_can_be_incremented()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->create();

        (new Cart($user))->add([
            ['id' => 1, 'quantity' => 1],
        ]);

        (new Cart($user->fresh()))->add([
            ['id' => 1, 'quantity' => 2],
        ]);

        $this->assertEquals(3, $user->fresh()->cart->first()->pivot->quantity);
    }

    /** @test */
    function cart_quantities_can_be_updated()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->create();

        (new Cart($user))->add([
            ['id' => 1, 'quantity' => 5],
        ]);

        (new Cart($user->fresh()))->update($product->id, 2);

        $this->assertEquals(2, $user->fresh()->cart->first()->pivot->quantity);
    }

    /** @test */
    function a_product_can_be_removed_from_the_cart()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->create();

        (new Cart($user))->add([
            ['id' => 1, 'quantity' => 5],
        ]);

        (new Cart($user->fresh()))->delete($product->id);

        $this->assertCount(0, $user->fresh()->cart);
    }

    /** @test */
    function cart_can_be_emptied()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->create();

        (new Cart($user))->add([
            ['id' => 1, 'quantity' => 5],
        ]);

        (new Cart($user->fresh()))->empty();

        $this->assertCount(0, $user->fresh()->cart);
    }

    /** @test */
    function is_empty_returns_proper_value()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $this->assertTrue($cart->isEmpty());
    }

    /** @test */
    function cart_returns_a_money_instance_for_the_subtotal()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $this->assertInstanceOf(Money::class, $cart->subtotal());
    }

    /** @test */
    function cart_subtotal_returns_correct_amount()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create([
                'price' => 1000,
            ]), [
                'quantity' => 2,
            ]
        );

        $this->assertEquals(2000, $cart->subtotal()->amount());
    }

    /** @test */
    function cart_returns_a_money_instance_for_the_total()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $this->assertInstanceOf(Money::class, $cart->total());
    }

    /** @test */
    function cart_syncs_the_quantities_of_items()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create(), [
                'quantity' => 2,
            ]
        );

        $cart->sync();

        $this->assertEquals(0, $user->fresh()->cart->first()->pivot->quantity);
        $this->assertTrue($cart->hasChanged());
    }
}
