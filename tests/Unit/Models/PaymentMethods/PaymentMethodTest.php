<?php

namespace Tests\Unit\Models\PaymentMethods;

use Tests\TestCase;
use App\Models\User;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_payment_method_belongs_to_a_user()
    {
        $paymentMethod = factory(PaymentMethod::class)->create([
            'user_id' => factory(User::class)->create()->id,
        ]);

        $this->assertInstanceOf(User::class, $paymentMethod->user);
    }

    /** @test */
    function a_payment_method_falsifies_old_defaults_when_creating_new_default()
    {
        $user = factory(User::class)->create();
        $oldPaymentMethod = factory(PaymentMethod::class)->create([
            'default' => true,
            'user_id' => 1,
        ]);

        factory(PaymentMethod::class)->create([
            'default' => true,
            'user_id' => 1,
        ]);

        $this->assertFalse($oldPaymentMethod->fresh()->default);
    }
}
