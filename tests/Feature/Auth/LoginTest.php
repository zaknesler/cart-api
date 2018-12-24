<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_authentication_requires_an_email()
    {
        $response = $this->json('POST', '/api/auth/login');

        $response->assertJsonValidationErrors(['email']);
    }

    /** @test */
    function user_authentication_requires_a_password()
    {
        $response = $this->json('POST', '/api/auth/login');

        $response->assertJsonValidationErrors(['password']);
    }

    /** @test */
    function user_authentication_requires_the_correct_password()
    {
        $user = factory(User::class)->create([
            'email' => 'zak@example.com',
            'password' => 'secret',
        ]);

        $response = $this->json('POST', '/api/auth/login', [
            'email' => 'zak@example.com',
            'password' => 'incorrect',
        ]);

        $response->assertJsonValidationErrors(['email']);
    }

    /** @test */
    function a_user_can_be_authenticated()
    {
        $user = factory(User::class)->create([
            'email' => 'zak@example.com',
            'password' => 'secret',
        ]);

        $response = $this->json('POST', '/api/auth/login', [
            'email' => 'zak@example.com',
            'password' => 'secret',
        ]);

        $response->assertJsonFragment([
            'id' => 1,
            'email' => 'zak@example.com',
        ]);

        $response->assertJsonStructure([
            'meta' => [
                'token',
            ],
        ]);
    }
}
