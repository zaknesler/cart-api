<?php

namespace Tests\Feature\Cart;

use App\Cart\Cart;
use Tests\TestCase;
use App\Models\User;
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
}
