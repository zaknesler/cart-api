<?php

namespace Tests\Feature\Addresses;

use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function must_be_authenticated_to_list_addresses()
    {
        $response = $this->json('GET', '/api/addresses');

        $response->assertStatus(401);
    }

    /** @test */
    function addresses_can_be_indexed()
    {
        $user = factory(User::class)->create();
        $address = factory(Address::class)->create([
            'user_id' => $user->id,
            'name' => 'Example Name',
        ]);

        $response = $this->jsonAs($user, 'GET', '/api/addresses');

        $response->assertJsonFragment([
            'id' => 1,
            'name' => 'Example Name',
        ]);
    }
}
