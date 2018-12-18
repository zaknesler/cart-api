<?php

namespace Tests\Feature\Product;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function the_products_index_endpoint_returns_a_successful_status_code()
    {
        $response = $this->get('/api/products');

        $response->assertSuccessful();
    }

    /** @test */
    function products_can_be_listed()
    {
        $products = factory(Product::class, 3)->create(['price' => 500]);

        $response = $this->get('/api/products');

        $products->each(function ($product) use ($response) {
            $response->assertJsonFragment([
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'price' => '$5.00',
            ]);
        });
    }

    /** @test */
    function products_are_paginated()
    {
        $response = $this->get('/api/products');

        $response->assertJsonStructure([
            'data',
            'links',
            'meta',
        ]);
    }
}
