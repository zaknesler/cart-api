<?php

namespace Tests\Unit\Listeners\PaymentMethods;

use Mockery;
use Tests\TestCase;
use App\Models\User;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\WithFaker;
use App\Cart\Payments\Stripe\StripePaymentGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Cart\Payments\Stripe\StripeGatewayCustomer;
use App\Events\PaymentMethods\PaymentMethodDeleted;
use App\Events\PaymentMethods\PaymentMethodRemovalFailed;
use App\Listeners\PaymentMethods\RemovePaymentMethodFromProvider;
use App\Exceptions\PaymentMethods\PaymentMethodRemovalFailedException;

class RemovePaymentMethodFromProviderListenerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function the_remove_payment_method_from_provider_listener_removes_the_card_from_the_provider_customer()
    {
        Event::fake();

        $user = factory(User::class)->create();

        $event = new PaymentMethodDeleted(
            $paymentMethod = factory(PaymentMethod::class)->create()
        );

        $gateway = Mockery::mock(StripePaymentGateway::class);
        $customer = Mockery::mock(StripeGatewayCustomer::class);

        $gateway->shouldReceive('withUser')
                ->andReturn($gateway)
                ->shouldReceive('getCustomer')
                ->andReturn($customer);

        $customer->shouldReceive('removeCard')
                ->with($paymentMethod)
                ->andReturn(true);

        $listener = new RemovePaymentMethodFromProvider($gateway);
        $listener->handle($event);
    }

    /** @test */
    function the_remove_payment_method_from_provider_listener_fires_the_payment_method_removal_failed_event()
    {
        Event::fake();

        $user = factory(User::class)->create();

        $event = new PaymentMethodDeleted(
            $paymentMethod = factory(PaymentMethod::class)->create()
        );

        $gateway = Mockery::mock(StripePaymentGateway::class);
        $customer = Mockery::mock(StripeGatewayCustomer::class);

        $gateway->shouldReceive('withUser')
                ->andReturn($gateway)
                ->shouldReceive('getCustomer')
                ->andReturn($customer);

        $customer->shouldReceive('removeCard')
                ->with($paymentMethod)
                ->andThrow(PaymentMethodRemovalFailedException::class);

        $listener = new RemovePaymentMethodFromProvider($gateway);
        $listener->handle($event);
    }
}
