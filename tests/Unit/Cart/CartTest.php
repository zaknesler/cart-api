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

        $cart->addProducts([
            ['id' => 1, 'quantity' => 1],
        ]);

        $this->assertCount(1, $user->fresh()->cart);
    }

    /** @test */
    function quantity_of_existing_products_in_the_cart_can_be_incremented()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->create();

        (new Cart($user))->addProducts([
            ['id' => 1, 'quantity' => 1],
        ]);

        (new Cart($user->fresh()))->addProducts([
            ['id' => 1, 'quantity' => 2],
        ]);

        $this->assertEquals(3, $user->fresh()->cart->first()->pivot->quantity);
    }
}
