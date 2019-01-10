<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use App\Models\Country;
use App\Models\CountryDivision;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function an_address_belongs_to_a_country()
    {
        $address = factory(Address::class)->create([
            'country_id' => factory(Country::class)->create()->id,
        ]);

        $this->assertInstanceOf(Country::class, $address->country);
    }

    /** @test */
    function an_address_belongs_to_a_country_division()
    {
        $address = factory(Address::class)->create([
            'country_division_id' => factory(CountryDivision::class)->create()->id,
        ]);

        $this->assertInstanceOf(CountryDivision::class, $address->countryDivision);
    }

    /** @test */
    function an_address_does_not_have_to_belong_to_a_country_division()
    {
        $address = factory(Address::class)->create([
            'country_division_id' => null,
        ]);

        $this->assertNull($address->countryDivision);
    }

    /** @test */
    function an_address_belongs_to_a_user()
    {
        $address = factory(Address::class)->create([
            'user_id' => factory(User::class)->create()->id,
        ]);

        $this->assertInstanceOf(User::class, $address->user);
    }

    /** @test */
    function an_address_falsifies_old_defaults_when_creating_new_default()
    {
        $user = factory(User::class)->create();

        $addressA = factory(Address::class)->create([
            'default' => true,
            'user_id' => 1,
        ]);

        $addressB = factory(Address::class)->create([
            'default' => true,
            'user_id' => 1,
        ]);

        $addressC = factory(Address::class)->create([
            'default' => true,
            'user_id' => 1,
        ]);

        $this->assertFalse($addressA->fresh()->default);
        $this->assertFalse($addressB->fresh()->default);
        $this->assertTrue($addressC->fresh()->default);
    }
}
