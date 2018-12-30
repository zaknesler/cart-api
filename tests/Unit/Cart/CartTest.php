<?php

namespace Tests\Unit\Cart;

use App\Cart\Cart;
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
}
