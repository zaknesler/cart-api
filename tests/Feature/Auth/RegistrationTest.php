<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_registration_requires_an_email()
    {
        $response = $this->json('POST', '/api/auth/register');

        $response->assertJsonValidationErrors(['email']);
    }

    /** @test */
    function user_registration_requires_a_valid_email()
    {
        $response = $this->json('POST', '/api/auth/register', [
            'email' => 'invalid',
        ]);

        $response->assertJsonValidationErrors(['email']);
    }

    /** @test */
    function user_registration_requires_a_unique_email()
    {
        $user = factory(User::class)->create(['email' => 'taken@example.com']);

        $response = $this->json('POST', '/api/auth/register', [
            'email' => 'taken@example.com',
        ]);

        $response->assertJsonValidationErrors(['email']);
    }

    /** @test */
    function user_registration_requires_a_name()
    {
        $response = $this->json('POST', '/api/auth/register');

        $response->assertJsonValidationErrors(['name']);
    }

    /** @test */
    function user_registration_requires_a_password()
    {
        $response = $this->json('POST', '/api/auth/register');

        $response->assertJsonValidationErrors(['password']);
    }

    /** @test */
    function user_registration_requires_a_password_at_least_eight_characters_long()
    {
        $user = factory(User::class)->create();

        $response = $this->json('POST', '/api/auth/register', [
            'password' => 'short',
        ]);

        $response->assertJsonValidationErrors(['password']);
    }

    /** @test */
    function a_user_can_be_registered()
    {
        $this->json('POST', '/api/auth/register', [
            'name' => 'Zak Nesler',
            'email' => 'zak@example.com',
            'password' => 'password',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'zak@example.com',
            'name' => 'Zak Nesler',
        ]);
    }

    /** @test */
    function a_user_resource_is_returned_upon_registration()
    {
        $response = $this->json('POST', '/api/auth/register', [
            'name' => 'Zak Nesler',
            'email' => 'zak@example.com',
            'password' => 'password',
        ]);

        $response->assertJsonFragment([
            'id' => 1,
            'name' => 'Zak Nesler',
            'email' => 'zak@example.com',
        ]);
    }
}
