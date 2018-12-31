<?php

namespace Tests\Feature\Cart;

use App\Cart\Cart;
use Tests\TestCase;
use App\Models\User;
use App\Models\Stock;
use App\Models\ProductVariation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function must_be_authenticated_to_index_the_cart()
    {
        $response = $this->json('GET', '/api/cart');

        $response->assertStatus(401);
    }

    /** @test */
    function products_can_be_indexed()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->create();

        $user->cart()->attach($product);

        $response = $this->jsonAs($user, 'GET', '/api/cart');

        $response->assertJsonFragment([
            'id' => 1,
        ]);
    }

    /** @test */
    function cart_shows_an_is_empty_attribute()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->states('stock')->create();

        $user->cart()->attach($product);

        $response = $this->jsonAs($user, 'GET', '/api/cart');

        $response->assertJsonFragment([
            'empty' => false,
        ]);
    }

    /** @test */
    function cart_index_shows_formatted_subtotal()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->states('stock')->create(['price' => 1000]);

        $user->cart()->attach($product);

        $response = $this->jsonAs($user, 'GET', '/api/cart');

        $response->assertJsonFragment([
            'subtotal' => '$10.00',
        ]);
    }

    /** @test */
    function cart_index_shows_formatted_total()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->states('stock')->create(['price' => 1000]);

        $user->cart()->attach($product);

        $response = $this->jsonAs($user, 'GET', '/api/cart');

        $response->assertJsonFragment([
            'total' => '$10.00',
        ]);
    }

    /** @test */
    function cart_index_syncs_product_quantities()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->create(['price' => 1000]);

        $user->cart()->attach($product);

        $response = $this->jsonAs($user, 'GET', '/api/cart');

        $response->assertJsonFragment([
            'changed' => true,
        ]);
    }
}
