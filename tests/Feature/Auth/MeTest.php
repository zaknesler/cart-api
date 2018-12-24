<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_guest_cannot_access_user_information()
    {
        $response = $this->json('GET', '/api/auth/me');

        $response->assertStatus(401);
    }

    /** @test */
    function a_user_can_access_their_details()
    {
        $user = factory(User::class)->create([
            'name' => 'Zak Nesler',
            'email' => 'zak@example.com',
        ]);

        $response = $this->jsonAs($user, 'GET', '/api/auth/me');

        $response->assertJsonFragment([
            'id' => 1,
            'name' => 'Zak Nesler',
            'email' => 'zak@example.com',
        ]);
    }
}
