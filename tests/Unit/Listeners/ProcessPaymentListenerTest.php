<?php

namespace Tests\Unit\Listeners;

use Mockery;
use Tests\TestCase;
use App\Models\Order;
use App\Events\Orders\OrderPaid;
use App\Events\Orders\OrderCreated;
use Illuminate\Support\Facades\Event;
use App\Listeners\Orders\ProcessPayment;
use App\Events\Orders\OrderPaymentFailed;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Exceptions\Payments\PaymentFailedException;
use App\Cart\Payments\Gateways\StripePaymentGateway;
use App\Cart\Payments\Gateways\StripeGatewayCustomer;

class ProcessPaymentListenerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function the_process_payment_listener_charges_the_payment_method_the_correct_amount()
    {
        Event::fake();

        $event = new OrderCreated(
            $order = factory(Order::class)->create()
        );

        $gateway = Mockery::mock(StripePaymentGateway::class);
        $customer = Mockery::mock(StripeGatewayCustomer::class);

        $gateway->shouldReceive('withUser')
            ->andReturn($gateway)
            ->shouldReceive('getCustomer')
            ->andReturn($customer);

        $customer->shouldReceive('charge')
            ->with(
                $order->paymentMethod,
                $order->total()->amount()
            );

        $listener = new ProcessPayment($gateway);
        $listener->handle($event);
    }

    /** @test */
    function the_process_payment_listener_fires_the_order_paid_event()
    {
        Event::fake();

        $event = new OrderCreated(
            $order = factory(Order::class)->create()
        );

        $gateway = Mockery::mock(StripePaymentGateway::class);
        $customer = Mockery::mock(StripeGatewayCustomer::class);

        $gateway->shouldReceive('withUser')
            ->andReturn($gateway)
            ->shouldReceive('getCustomer')
            ->andReturn($customer);

        $customer->shouldReceive('charge')
            ->with(
                $order->paymentMethod,
                $order->total()->amount()
            );

        $listener = new ProcessPayment($gateway);
        $listener->handle($event);

        Event::assertDispatched(OrderPaid::class, function ($event) use ($order) {
            return $event->order->id === $order->id;
        });
    }

    /** @test */
    function the_process_payment_listener_fires_the_order_payment_failed_event()
    {
        Event::fake();

        $event = new OrderCreated(
            $order = factory(Order::class)->create()
        );

        $gateway = Mockery::mock(StripePaymentGateway::class);
        $customer = Mockery::mock(StripeGatewayCustomer::class);

        $gateway->shouldReceive('withUser')
            ->andReturn($gateway)
            ->shouldReceive('getCustomer')
            ->andReturn($customer);

        $customer->shouldReceive('charge')
            ->with(
                $order->paymentMethod,
                $order->total()->amount()
            )
            ->andThrow(PaymentFailedException::class);

        $listener = new ProcessPayment($gateway);
        $listener->handle($event);

        Event::assertDispatched(OrderPaymentFailed::class, function ($event) use ($order) {
            return $event->order->id === $order->id;
        });
    }
}
