<?php

namespace Tests\Feature\PaymentMethods;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentMethodStoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_must_be_authenticated_to_store_a_new_payment_method()
    {
        $response = $this->json('POST', '/api/payment-methods');

        $response->assertStatus(401);
    }

    /** @test */
    function storing_a_payment_method_reqiures_a_token()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'POST', '/api/payment-methods');

        $response->assertJsonValidationErrors('token');
    }

    /** @test */
    function storing_a_payment_method_reqiures_a_valid_token_format()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'POST', '/api/payment-methods', [
            'token' => 'invalid',
        ]);

        $response->assertJsonValidationErrors('token');
    }

    /**
     * @test
     * @group hits-stripe
    */
    function a_user_can_store_a_payment_method()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'POST', '/api/payment-methods', [
            'token' => 'tok_visa',
        ]);

        $this->assertDatabaseHas('payment_methods', [
            'user_id' => 1,
            'card_type' => 'Visa',
            'last_four' => '4242',
        ]);
    }

    /**
     * @test
     * @group hits-stripe
    */
    function storing_a_payment_method_returns_the_created_card()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'POST', '/api/payment-methods', [
            'token' => 'tok_visa',
        ]);

        $response->assertJsonFragment([
            'id' => 1,
            'card_type' => 'Visa',
            'last_four' => '4242',
        ]);
    }

    /**
     * @test
     * @group hits-stripe
    */
    function storing_a_payment_method_sets_the_created_card_as_default()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'POST', '/api/payment-methods', [
            'token' => 'tok_visa',
        ]);

        $this->assertDatabaseHas('payment_methods', [
            'id' => 1,
            'default' => true,
        ]);
    }
}
