<?php

namespace Tests\Feature\Cart;

use App\Cart\Cart;
use Tests\TestCase;
use App\Models\User;
use App\Models\Stock;
use App\Models\ShippingMethod;
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
        $product = factory(ProductVariation::class)->states('stocked')->create();

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
        $product = factory(ProductVariation::class)->states('stocked')->create();

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
        $product = factory(ProductVariation::class)->states('stocked')->create(['price' => 1000]);

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
        $product = factory(ProductVariation::class)->states('stocked')->create(['price' => 1000]);

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

        $user->cart()->attach(
            factory(ProductVariation::class)->create()
        );

        $response = $this->jsonAs($user, 'GET', '/api/cart');

        $response->assertStatus(409);
    }

    /** @test */
    function shipping_method_id_must_exist_if_supplied()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'GET', '/api/cart?shipping_method_id=1');

        $response->assertJsonValidationErrors('shipping_method_id');
    }

    /** @test */
    function shipping_method_id_must_be_an_integer_if_supplied()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'GET', '/api/cart?shipping_method_id=no');

        $response->assertJsonValidationErrors('shipping_method_id');
    }

    /** @test */
    function cart_index_total_adds_on_correct_amount_for_shipping_method()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->state('stocked')->create(['price' => 1000]);
        $shippingMethod = factory(ShippingMethod::class)->create(['price' => 500]);

        $user->cart()->attach($product);

        $response = $this->jsonAs($user, 'GET', '/api/cart?shipping_method_id=1');

        $response->assertJsonFragment([
            'total' => '$15.00',
        ]);
    }
}
