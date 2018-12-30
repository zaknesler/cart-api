<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Models\ProductVariation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartStoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function must_be_authenticated()
    {
        $response = $this->json('POST', '/api/cart');

        $response->assertStatus(401);
    }

    /** @test */
    function products_must_be_provided()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'POST', '/api/cart');

        $response->assertJsonValidationErrors(['products']);
    }

    /** @test */
    function products_must_be_provided_as_an_array()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'POST', '/api/cart', [
            'products' => 1,
        ]);

        $response->assertJsonValidationErrors(['products']);
    }

    /** @test */
    function products_must_be_provided_with_an_id()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'POST', '/api/cart', [
            'products' => [
                ['quantity' => 1],
            ],
        ]);

        $response->assertJsonValidationErrors(['products.0.id']);
    }

    /** @test */
    function products_must_be_provided_with_an_id_of_an_existing_product()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'POST', '/api/cart', [
            'products' => [
                ['id' => 1, 'quantity' => 1],
            ],
        ]);

        $response->assertJsonValidationErrors(['products.0.id']);
    }

    /** @test */
    function products_must_be_provided_with_a_numeric_quantity()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'POST', '/api/cart', [
            'products' => [
                ['id' => 1, 'quantity' => 'one'],
            ],
        ]);

        $response->assertJsonValidationErrors(['products.0.quantity']);
    }

    /** @test */
    function products_must_be_provided_with_a_quantity_of_at_least_one()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'POST', '/api/cart', [
            'products' => [
                ['id' => 1, 'quantity' => 0],
            ],
        ]);

        $response->assertJsonValidationErrors(['products.0.quantity']);
    }

    /** @test */
    function a_product_can_be_added_to_the_cart()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->create();

        $response = $this->jsonAs($user, 'POST', '/api/cart', [
            'products' => [
                ['id' => 1, 'quantity' => 3],
            ],
        ]);

        $this->assertDatabaseHas('user_cart', [
            'product_variation_id' => 1,
            'quantity' => 3,
        ]);
    }
}
