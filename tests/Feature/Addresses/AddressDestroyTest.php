<?php

namespace Tests\Feature\Addresses;

use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressDestroyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function must_be_authenticated_to_delete_addresses()
    {
        $response = $this->json('DELETE', '/api/addresses/1');

        $response->assertStatus(401);
    }

    /** @test */
    function a_user_can_soft_delete_an_address()
    {
        $user = factory(User::class)->create();
        $address = factory(Address::class)->create([
            'user_id' => 1,
        ]);

        $response = $this->jsonAs($user, 'DELETE', '/api/addresses/1');

        $this->assertDatabaseMissing('addresses', [
            'id' => 1,
            'deleted_at' => null,
        ]);
    }

    /** @test */
    function a_user_must_own_an_address_to_delete_it()
    {
        $user = factory(User::class)->create();
        $address = factory(Address::class)->create();

        $response = $this->jsonAs($user, 'DELETE', '/api/addresses/1');

        $response->assertStatus(403);
    }

    /** @test */
    function if_an_address_is_set_as_default_the_first_remaining_address_if_exists_is_set_as_the_new_default()
    {
        $user = factory(User::class)->create();
        $addressA = factory(Address::class)->create([
            'user_id' => $user->id,
            'default' => true,
        ]);
        $addressB = factory(Address::class)->create([
            'user_id' => $user->id,
            'default' => false,
        ]);

        $response = $this->jsonAs($user, 'DELETE', "/api/addresses/{$addressA->id}");

        $this->assertDatabaseMissing('addresses', [
            'id' => $addressA->id,
            'default' => true,
        ]);

        $this->assertDatabaseHas('addresses', [
            'id' => $addressB->id,
            'default' => true,
        ]);
    }

    /** @test */
    function a_user_can_delete_a_single_address_without_another_becoming_default()
    {
        $user = factory(User::class)->create();
        $addressA = factory(Address::class)->create([
            'user_id' => $user->id,
            'default' => true,
        ]);

        $response = $this->jsonAs($user, 'DELETE', "/api/addresses/{$addressA->id}");

        $this->assertDatabaseMissing('addresses', [
            'default' => true,
        ]);
    }
}
