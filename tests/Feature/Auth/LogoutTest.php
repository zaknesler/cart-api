<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_must_be_authenticated_to_logout()
    {
        $response = $this->json('DELETE', '/api/auth/logout');

        $response->assertStatus(401);
    }

    /** @test */
    function a_user_can_be_signed_out()
    {
        $user = factory(User::class)->create();
        $token = auth()->byId($user->id);

        $this->assertTrue(auth()->check());

        $response = $this->jsonAs($user, 'DELETE', '/api/auth/logout');

        $this->assertFalse(auth()->check());
    }
}
