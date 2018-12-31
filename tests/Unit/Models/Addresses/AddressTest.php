<?php

namespace Tests\Unit\Models\Addresses;

use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use App\Models\Country;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function an_address_has_one_country()
    {
        $address = factory(Address::class)->create([
            'user_id' => factory(User::class)->create()->id,
        ]);

        $this->assertInstanceOf(Country::class, $address->country);
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
        $oldAddress = factory(Address::class)->create([
            'default' => true,
            'user_id' => 1,
        ]);

        factory(Address::class)->create([
            'default' => true,
            'user_id' => 1,
        ]);

        $this->assertFalse($oldAddress->fresh()->default);
    }
}
