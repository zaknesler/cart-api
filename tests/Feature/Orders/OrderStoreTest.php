<?php

namespace Tests\Feature\Orders;

use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use App\Models\Country;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use App\Models\ProductVariation;
use App\Events\Orders\OrderCreated;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderStoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_must_be_authenticated_to_store_an_order()
    {
        $response = $this->json('POST', '/api/orders');

        $response->assertStatus(401);
    }

    /** @test */
    function storing_an_order_requires_an_address()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->states('stocked')->create();

        $user->cart()->attach($product, [
            'quantity' => 1,
        ]);

        $response = $this->jsonAs($user, 'POST', '/api/orders');

        $response->assertJsonValidationErrors('address_id');
    }

    /** @test */
    function storing_an_order_requires_an_address_that_exists()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->states('stocked')->create();

        $user->cart()->attach($product, [
            'quantity' => 1,
        ]);

        $response = $this->jsonAs($user, 'POST', '/api/orders', [
            'address_id' => 1,
        ]);

        $response->assertJsonValidationErrors('address_id');
    }

    /** @test */
    function storing_an_order_requires_an_address_that_belongs_to_the_authenticated_user()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->states('stocked')->create();

        $user->cart()->attach($product, [
            'quantity' => 1,
        ]);

        $address = factory(Address::class)->create([
            'user_id' => factory(User::class)->create()->id,
        ]);

        $response = $this->jsonAs($user, 'POST', '/api/orders', [
            'address_id' => 1,
        ]);

        $response->assertJsonValidationErrors('address_id');
    }

    /** @test */
    function storing_an_order_requires_a_shipping_method()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->states('stocked')->create();

        $user->cart()->attach($product, [
            'quantity' => 1,
        ]);

        $response = $this->jsonAs($user, 'POST', '/api/orders');

        $response->assertJsonValidationErrors('shipping_method_id');
    }

    /** @test */
    function storing_an_order_requires_a_shipping_method_that_exists()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->states('stocked')->create();

        $user->cart()->attach($product, [
            'quantity' => 1,
        ]);

        $response = $this->jsonAs($user, 'POST', '/api/orders', [
            'shipping_method_id' => 1,
        ]);

        $response->assertJsonValidationErrors('shipping_method_id');
    }

    /** @test */
    function storing_an_order_requires_a_payment_method()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->states('stocked')->create();

        $user->cart()->attach($product, [
            'quantity' => 1,
        ]);

        $response = $this->jsonAs($user, 'POST', '/api/orders');

        $response->assertJsonValidationErrors('payment_method_id');
    }

    /** @test */
    function storing_an_order_requires_a_shipping_method_valid_for_the_given_address()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->states('stocked')->create();

        $user->cart()->attach($product, [
            'quantity' => 1,
        ]);

        $shippingMethod = factory(ShippingMethod::class)->create();
        $address = factory(Address::class)->create(['user_id' => 1]);

        $response = $this->jsonAs($user, 'POST', '/api/orders', [
            'shipping_method_id' => 1,
            'address_id' => 1,
        ]);

        $response->assertJsonValidationErrors('shipping_method_id');
    }

    /** @test */
    function storing_an_order_requires_a_payment_method_that_exists()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->states('stocked')->create();

        $user->cart()->attach($product, [
            'quantity' => 1,
        ]);

        $response = $this->jsonAs($user, 'POST', '/api/orders', [
            'payment_method_id' => 1,
        ]);

        $response->assertJsonValidationErrors('payment_method_id');
    }

    /** @test */
    function user_cannot_store_an_order_if_their_cart_is_empty()
    {
        $user = factory(User::class)->create();

        $user->cart()->sync([
            factory(ProductVariation::class)->state('stocked')->create()->id => [
                'quantity' => 0,
            ],
        ]);

        $shippingMethod = factory(ShippingMethod::class)->create();
        $address = factory(Address::class)->create(['user_id' => 1]);
        $shippingMethod->countries()->attach($address->country);

        $response = $this->jsonAs($user, 'POST', '/api/orders', [
            'address_id' => 1,
            'shipping_method_id' => 1,
        ]);

        $response->assertStatus(400);
    }

    /** @test */
    function a_user_can_create_an_order()
    {
        $user = factory(User::class)->create();
        $shippingMethod = factory(ShippingMethod::class)->create();
        $address = factory(Address::class)->create(['user_id' => 1]);
        $shippingMethod->countries()->attach($address->country);

        $user->cart()->sync(
            factory(ProductVariation::class)->state('stocked')->create()
        );

        $this->jsonAs($user, 'POST', '/api/payment-methods', [
            'token' => 'tok_visa',
        ]);

        $response = $this->jsonAs($user, 'POST', '/api/orders', [
            'address_id' => 1,
            'shipping_method_id' => 1,
            'payment_method_id' => 1,
        ]);

        $response->assertSuccessful();
        $this->assertDatabaseHas('orders', [
            'user_id' => 1,
            'address_id' => 1,
            'shipping_method_id' => 1,
            'payment_method_id' => 1,
        ]);
    }

    /** @test */
    function products_are_attached_to_the_created_order()
    {
        $user = factory(User::class)->create();
        $shippingMethod = factory(ShippingMethod::class)->create();
        $address = factory(Address::class)->create(['user_id' => 1]);
        $shippingMethod->countries()->attach($address->country);

        $user->cart()->sync(
            $product = factory(ProductVariation::class)->state('stocked')->create()
        );

        $this->jsonAs($user, 'POST', '/api/payment-methods', [
            'token' => 'tok_visa',
        ]);

        $response = $this->jsonAs($user, 'POST', '/api/orders', [
            'address_id' => 1,
            'shipping_method_id' => 1,
            'payment_method_id' => 1,
        ]);

        $this->assertDatabaseHas('order_product_variation', [
            'product_variation_id' => 1,
            'order_id' => 1,
        ]);
    }

    /** @test */
    function order_created_event_is_fired_when_order_is_stored()
    {
        Event::fake();

        $user = factory(User::class)->create();
        $shippingMethod = factory(ShippingMethod::class)->create();
        $address = factory(Address::class)->create(['user_id' => 1]);
        $shippingMethod->countries()->attach($address->country);

        $user->cart()->sync(
            $product = factory(ProductVariation::class)->state('stocked')->create()
        );

        $this->jsonAs($user, 'POST', '/api/payment-methods', [
            'token' => 'tok_visa',
        ]);

        $response = $this->jsonAs($user, 'POST', '/api/orders', [
            'address_id' => 1,
            'shipping_method_id' => 1,
            'payment_method_id' => 1,
        ]);

        Event::assertDispatched(OrderCreated::class, function ($event) {
            return $event->order->id === 1;
        });
    }

    /** @test */
    function after_an_order_is_stored_the_users_cart_is_emptied()
    {
        $user = factory(User::class)->create();
        $shippingMethod = factory(ShippingMethod::class)->create();
        $address = factory(Address::class)->create(['user_id' => 1]);
        $shippingMethod->countries()->attach($address->country);

        $user->cart()->sync(
            $product = factory(ProductVariation::class)->state('stocked')->create()
        );

        $this->jsonAs($user, 'POST', '/api/payment-methods', [
            'token' => 'tok_visa',
        ]);

        $response = $this->jsonAs($user, 'POST', '/api/orders', [
            'address_id' => 1,
            'shipping_method_id' => 1,
            'payment_method_id' => 1,
        ]);

        $this->assertTrue($user->cart->isEmpty());
    }
}
