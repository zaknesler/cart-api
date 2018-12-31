<?php

namespace Tests\Feature\Addresses;

use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use App\Models\Country;
use App\Models\ShippingMethod;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressShippingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_must_be_authenticated_to_view_addresses()
    {
        $response = $this->json('GET', '/api/addresses/1/shipping');

        $response->assertStatus(401);
    }

    /** @test */
    function an_address_must_exist_to_be_viewed()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'GET', '/api/addresses/1/shipping');

        $response->assertStatus(404);
    }

    /** @test */
    function an_address_must_belong_to_a_user_to_be_viewed()
    {
        $user = factory(User::class)->create();
        $address = factory(Address::class)->create([
            'user_id' => factory(User::class)->create()->id,
        ]);

        $response = $this->jsonAs($user, 'GET', '/api/addresses/1/shipping');

        $response->assertStatus(403);
    }

    /** @test */
    function shipping_methods_can_be_indexed_for_a_specific_address()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class)->create();
        $address = factory(Address::class)->create([
            'user_id' => 1,
            'country_id' => 1,
        ]);

        $country->shippingMethods()->save(
            $shippingMethod = factory(ShippingMethod::class)->create()
        );

        $response = $this->jsonAs($user, 'GET', '/api/addresses/1/shipping');

        $response->assertJsonFragment([
            'id' => 1,
        ]);
    }
}
