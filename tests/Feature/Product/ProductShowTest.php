<?php

namespace Tests\Feature\Product;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function the_product_show_endpoint_returns_a_successful_status_code()
    {
        factory(Product::class)->create([ 'slug' => 'example' ]);

        $response = $this->json('GET', '/api/products/example');

        $response->assertSuccessful();
    }

    /** @test */
    function products_that_are_not_found_will_return_an_unsuccessful_status_code()
    {
        $response = $this->json('GET', '/api/products/does-not-exist');

        $response->assertNotFound();
    }

    /** @test */
    function a_product_can_be_viewed()
    {
        factory(Product::class)->create([
            'name' => 'Example Product',
            'slug' => 'example-product',
            'description' => 'Just an example product',
            'price' => 650,
        ]);

        $response = $this->json('GET', '/api/products/example-product');

        $response->assertJsonFragment([
            'name' => 'Example Product',
            'slug' => 'example-product',
            'description' => 'Just an example product',
            'price' => '$6.50',
        ]);
    }
}
