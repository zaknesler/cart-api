<?php

namespace Tests\Unit\Models\Users;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_password_is_hashed_on_creation()
    {
        $user = factory(User::class)->create([
            'password' => 'secret-password',
        ]);

        $this->assertNotEquals('secret-password', $user->password);
    }
}
