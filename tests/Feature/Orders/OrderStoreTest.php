<?php

namespace Tests\Feature\Orders;

use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use App\Models\Country;
use App\Models\ShippingMethod;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderStoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_must_be_authenticated_to_store_an_order()
    {
        $response = $this->json('POST', '/api/orders');

        $response->assertStatus(401);
    }

    /** @test */
    function storing_an_order_requires_an_address()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'POST', '/api/orders');

        $response->assertJsonValidationErrors('address_id');
    }

    /** @test */
    function storing_an_order_requires_an_address_that_exists()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'POST', '/api/orders', [
            'address_id' => 1,
        ]);

        $response->assertJsonValidationErrors('address_id');
    }

    /** @test */
    function storing_an_order_requires_an_address_that_belongs_to_the_authenticated_user()
    {
        $user = factory(User::class)->create();
        $address = factory(Address::class)->create([
            'user_id' => factory(User::class)->create()->id,
        ]);

        $response = $this->jsonAs($user, 'POST', '/api/orders', [
            'address_id' => 1,
        ]);

        $response->assertJsonValidationErrors('address_id');
    }

    /** @test */
    function storing_an_order_requires_a_shipping_method()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'POST', '/api/orders');

        $response->assertJsonValidationErrors('shipping_method_id');
    }

    /** @test */
    function storing_an_order_requires_a_shipping_method_that_exists()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'POST', '/api/orders', [
            'shipping_method_id' => 1,
        ]);

        $response->assertJsonValidationErrors('shipping_method_id');
    }

    /** @test */
    function storing_an_order_requires_a_shipping_method_valid_for_the_given_address()
    {
        $user = factory(User::class)->create();
        $shippingMethod = factory(ShippingMethod::class)->create();
        $address = factory(Address::class)->create([
            'user_id' => 1,
        ]);

        $response = $this->jsonAs($user, 'POST', '/api/orders', [
            'shipping_method_id' => 1,
            'address_id' => 1,
        ]);

        $response->assertJsonValidationErrors('shipping_method_id');
    }
}
