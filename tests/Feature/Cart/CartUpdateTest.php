<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Models\ProductVariation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function must_be_authenticated_to_update_a_product()
    {
        $response = $this->json('PATCH', '/api/cart/1');

        $response->assertStatus(401);
    }

    /** @test */
    function product_must_exist_when_trying_to_update_it()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'PATCH', '/api/cart/1');

        $response->assertStatus(404);
    }

    /** @test */
    function a_quantity_is_required_to_update_a_product_in_the_cart()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->states('stocked')->create();

        $response = $this->jsonAs($user, 'PATCH', '/api/cart/1');

        $response->assertJsonValidationErrors(['quantity']);
    }

    /** @test */
    function a_quantity_of_one_or_more_is_required_to_update_a_product_in_the_cart()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->states('stocked')->create();

        $response = $this->jsonAs($user, 'PATCH', '/api/cart/1', [
            'quantity' => 0,
        ]);

        $response->assertJsonValidationErrors(['quantity']);
    }

    /** @test */
    function a_product_in_the_cart_can_be_updated()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->states('stocked')->create();

        $user->cart()->attach($product, [
            'quantity' => 1,
        ]);

        $response = $this->jsonAs($user, 'PATCH', '/api/cart/1', [
            'quantity' => 5,
        ]);

        $this->assertDatabaseHas('user_cart', [
            'product_variation_id' => 1,
            'quantity' => 5,
        ]);
    }
}
