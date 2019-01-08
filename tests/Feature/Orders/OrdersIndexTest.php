<?php

namespace Tests\Feature\Orders;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrdersIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_must_be_authenticated_to_index_orders()
    {
        $response = $this->json('GET', '/api/orders');

        $response->assertStatus(401);
    }

    /** @test */
    function indexing_orders_returns_a_collection_of_orders()
    {
        $user = factory(User::class)->create();

        factory(Order::class)->create([
            'user_id' => 1,
        ]);

        $response = $this->jsonAs($user, 'GET', '/api/orders');

        $response->assertJsonFragment([
            'id' => 1,
        ]);
    }

    /** @test */
    function indexing_orders_will_order_by_latest_first()
    {
        $user = factory(User::class)->create();

        $orderA = factory(Order::class)->create([
            'user_id' => 1,
        ]);

        $orderB = factory(Order::class)->create([
            'user_id' => 1,
            'created_at' => now()->subDay(),
        ]);

        $response = $this->jsonAs($user, 'GET', '/api/orders');

        $response->assertSeeInOrder([
            $orderA->created_at->toDateTimeString(),
            $orderB->created_at->toDateTimeString(),
        ]);
    }

    /** @test */
    function indexing_orders_return_a_paginated_list()
    {
        $user = factory(User::class)->create();

        $response = $this->jsonAs($user, 'GET', '/api/orders');

        $response->assertJsonStructure([
            'links',
            'meta',
        ]);
    }
}
