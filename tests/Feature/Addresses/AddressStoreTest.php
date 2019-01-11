<?php

namespace Tests\Feature\Addresses;

use Tests\TestCase;
use App\Models\User;
use App\Models\Country;
use App\Models\CountryDivision;
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
    function using_a_country_division_requires_one_that_exists()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'POST', '/api/addresses', [
            'country_division_id' => 1,
        ]);

        $response->assertJsonValidationErrors('country_division_id');
    }

    /** @test */
    function country_division_must_belong_to_a_country_that_exists()
    {
        $user = factory(User::class)->create();
        $countryDivision = factory(CountryDivision::class)->create();

        $response = $this->jsonAs($user, 'POST', '/api/addresses', [
            'country_division_id' => 1,
        ]);

        $response->assertJsonValidationErrors('country_division_id');
    }

    /** @test */
    function country_division_must_belong_to_the_specified_country()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class)->create();
        $countryDivision = factory(CountryDivision::class)->create();

        $response = $this->jsonAs($user, 'POST', '/api/addresses', [
            'country_id' => 1,
            'country_division_id' => 1,
        ]);

        $response->assertJsonValidationErrors('country_division_id');
    }

    /** @test */
    function a_country_division_is_required_if_the_specified_country_has_divisions()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class)->create([
            'code' => 'FK',
            'name' => 'Fake Country',
            'has_divisions' => true,
        ]);

        $response = $this->jsonAs($user, 'POST', '/api/addresses', [
            'country_id' => 1,
        ]);

        $response->assertJsonValidationErrors('country_division_id');
    }

    /** @test */
    function a_user_can_store_an_address()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class)->create([
            'code' => 'FK',
            'name' => 'Fake Country',
            'has_divisions' => true,
            'division_type' => 'Fake',
        ]);
        $countryDivision = factory(CountryDivision::class)->create([
            'country_id' => 1,
            'name' => 'Fake Country Division',
            'code' => 'FK',
        ]);

        $response = $this->jsonAs($user, 'POST', '/api/addresses', [
            'name' => 'Zak Nesler',
            'address_1' => '123 Sunnyside Lane',
            'address_2' => 'Some other data',
            'city' => 'Fakeville',
            'postal_code' => '12345',
            'country_id' => 1,
            'country_division_id' => 1,
        ]);

        $response->assertSuccessful();
        $this->assertDatabaseHas('addresses', [
            'user_id' => 1,
            'name' => 'Zak Nesler',
            'address_1' => '123 Sunnyside Lane',
            'address_2' => 'Some other data',
            'city' => 'Fakeville',
            'postal_code' => '12345',
            'country_id' => 1,
            'country_division_id' => 1,
        ]);
    }

    /** @test */
    function storing_an_address_returns_an_address_resource()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class)->create([
            'code' => 'FK',
            'name' => 'Fake Country',
            'has_divisions' => true,
            'division_type' => 'Fake',
        ]);
        $countryDivision = factory(CountryDivision::class)->create([
            'country_id' => 1,
            'name' => 'Fake Country Division',
            'code' => 'FK',
        ]);

        $response = $this->jsonAs($user, 'POST', '/api/addresses', [
            'name' => 'Zak Nesler',
            'address_1' => '123 Sunnyside Lane',
            'address_2' => 'Some other data',
            'city' => 'Fakeville',
            'postal_code' => '12345',
            'country_id' => 1,
            'country_division_id' => 1,
        ]);

        $response->assertSuccessful();
        $response->assertJsonFragment([
            'id' => 1,
        ]);
    }

    /** @test */
    function an_address_can_be_stored_without_a_country_division_if_the_country_does_not_have_any_divisions()
    {
        $user = factory(User::class)->create();
        $country = factory(Country::class)->create([
            'code' => 'FK',
            'name' => 'Fake Country',
            'has_divisions' => false,
        ]);

        $response = $this->jsonAs($user, 'POST', '/api/addresses', [
            'name' => 'Zak Nesler',
            'address_1' => '123 Sunnyside Lane',
            'address_2' => 'Some other data',
            'city' => 'Fakeville',
            'postal_code' => '12345',
            'country_id' => 1,
        ]);

        $response->assertSuccessful();
        $this->assertDatabaseMissing('addresses', [
            'country_division_id' => 1,
        ]);
    }
}
