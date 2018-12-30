<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Models\ProductVariation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartDestroyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function must_be_authenticated_to_remove_a_product()
    {
        $response = $this->json('DELETE', '/api/cart/1');

        $response->assertStatus(401);
    }

    /** @test */
    function product_must_exist_when_trying_to_remove_it()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'DELETE', '/api/cart/1');

        $response->assertStatus(404);
    }

    /** @test */
    function a_product_in_the_cart_can_be_removed()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->create();

        $user->cart()->attach($product, [
            'quantity' => 1,
        ]);

        $response = $this->jsonAs($user, 'DELETE', '/api/cart/1');

        $this->assertDatabaseMissing('user_cart', [
            'product_variation_id' => 1,
        ]);
    }
}
