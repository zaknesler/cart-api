<?php

namespace Tests\Feature\PaymentMethods;

use Tests\TestCase;
use App\Models\User;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Events\PaymentMethods\PaymentMethodDeleted;

class PaymentMethodDestroyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function must_be_authenticated_to_delete_payment_methods()
    {
        $response = $this->json('DELETE', '/api/payment-methods/1');

        $response->assertStatus(401);
    }

    /** @test */
    function a_user_must_own_a_payment_method_to_delete_it()
    {
        $user = factory(User::class)->create();
        $paymentMethod = factory(PaymentMethod::class)->create();

        $response = $this->jsonAs($user, 'DELETE', '/api/payment-methods/1');

        $response->assertStatus(403);
    }

    /** @test */
    function a_user_can_soft_delete_a_payment_method()
    {
        $user = factory(User::class)->create();
        factory(PaymentMethod::class)->create(['user_id' => 1]);

        $response = $this->jsonAs($user, 'DELETE', '/api/payment-methods/1');

        $this->assertDatabaseMissing('payment_methods', [
            'id' => 1,
            'deleted_at' => null,
        ]);
    }

    /**
     * @test
     * @group hits-stripe
     */
    function if_a_payment_method_is_set_as_default_the_most_recent_payment_method_is_set_as_the_new_default()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'POST', '/api/payment-methods', [
            'token' => 'tok_visa',
        ]);

        $this->jsonAs($user, 'POST', '/api/payment-methods', [
            'token' => 'tok_visa',
        ]);

        $response = $this->jsonAs($user, 'DELETE', "/api/payment-methods/1");

        $this->assertDatabaseHas('payment_methods', [
            'id' => 1,
            'default' => false,
        ]);

        $this->assertDatabaseHas('payment_methods', [
            'id' => 2,
            'default' => true,
        ]);
    }

    /**
     * @test
     * @group hits-stripe
     */
    function payment_method_deleted_event_is_fired_upon_deletion_of_payment_method()
    {
        Event::fake();

        $user = factory(User::class)->create();

        $this->jsonAs($user, 'POST', '/api/payment-methods', [
            'token' => 'tok_visa',
        ]);

        $response = $this->jsonAs($user, 'DELETE', "/api/payment-methods/1");

        Event::assertDispatched(PaymentMethodDeleted::class, function ($event) {
            return $event->paymentMethod->id === 1;
        });
    }
}
