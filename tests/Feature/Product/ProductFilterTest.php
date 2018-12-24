<?php

namespace Tests\Feature\Product;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductFilterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_product_can_be_filtered_by_a_category()
    {
        factory(Product::class, 2)->create();
        factory(Category::class, 2)->create();

        $product = factory(Product::class)->create([ 'name' => 'Test Product' ]);

        $product->categories()->save(
            factory(Category::class)->create([ 'slug' => 'example' ])
        );

        $response = $this->json('GET', '/api/products?category=example');

        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment([
            'name' => 'Test Product',
        ]);
    }
}
