<?php

namespace Tests\Feature\PaymentMethods;

use Tests\TestCase;
use App\Models\User;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentMethodIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function must_be_authenticated_to_index_payment_methods()
    {
        $response = $this->json('GET', '/api/payment-methods');

        $response->assertStatus(401);
    }

    /** @test */
    function payment_method_indexing_returns_a_collection_of_payment_methods()
    {
        $user = factory(User::class)->create();

        factory(PaymentMethod::class)->create([
            'user_id' => 1,
            'card_type' => 'Fake Type',
        ]);

        $response = $this->jsonAs($user, 'GET', '/api/payment-methods');

        $response->assertJsonFragment([
            'id' => 1,
            'card_type' => 'Fake Type',
        ]);
    }
}
