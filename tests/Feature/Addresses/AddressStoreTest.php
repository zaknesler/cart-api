<?php

namespace Tests\Feature\Addresses;

use Tests\TestCase;
use App\Models\User;
use App\Models\Country;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressStoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function must_be_authenticated_to_store_an_address()
    {
        $response = $this->json('POST', '/api/addresses');

        $response->assertStatus(401);
    }

    /** @test */
    function storing_an_address_requires_a_name()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'POST', '/api/addresses');

        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    function storing_an_address_requires_address_line_one()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'POST', '/api/addresses');

        $response->assertJsonValidationErrors('address_1');
    }

    /** @test */
    function storing_an_address_requires_a_city()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'POST', '/api/addresses');

        $response->assertJsonValidationErrors('city');
    }

    /** @test */
    function storing_an_address_requires_a_postal_code()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'POST', '/api/addresses');

        $response->assertJsonValidationErrors('postal_code');
    }

    /** @test */
    function storing_an_address_requires_a_country()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'POST', '/api/addresses');

        $response->assertJsonValidationErrors('country_id');
    }

    /** @test */
    function storing_an_address_requires_a_country_that_exists()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'POST', '/api/addresses', [
            'country_id' => 1,
        ]);

        $response->assertJsonValidationErrors('country_id');
    }

    /** @test */
    function a_user_can_store_an_address()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class)->create([
            'code' => 'FK',
            'name' => 'Fake Country',
        ]);

        $response = $this->jsonAs($user, 'POST', '/api/addresses', [
            'name' => 'Zak Nesler',
            'address_1' => '123 Sunnyside Lane',
            'city' => 'Fakeville',
            'postal_code' => '12345',
            'country_id' => $country->id,
        ]);

        $this->assertDatabaseHas('addresses', [
            'user_id' => 1,
            'name' => 'Zak Nesler',
            'address_1' => '123 Sunnyside Lane',
            'city' => 'Fakeville',
            'postal_code' => '12345',
            'country_id' => 1,
        ]);
    }

    /** @test */
    function storing_an_address_returns_an_address_resource()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class)->create([
            'code' => 'FK',
            'name' => 'Fake Country',
        ]);

        $response = $this->jsonAs($user, 'POST', '/api/addresses', [
            'name' => 'Zak Nesler',
            'address_1' => '123 Sunnyside Lane',
            'city' => 'Fakeville',
            'postal_code' => '12345',
            'country_id' => $country->id,
        ]);

        $response->assertJsonFragment([
            'id' => 1,
            'name' => 'Zak Nesler',
        ]);
    }
}
