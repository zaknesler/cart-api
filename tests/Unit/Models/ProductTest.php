<?php

namespace Tests\Unit\Models;

use App\Cart\Money;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function model_uses_the_slug_as_the_route_key_name()
    {
        $product = new Product();

        $this->assertEquals($product->getRouteKeyName(), 'slug');
    }

    /** @test */
    function a_product_can_have_many_categories()
    {
        $product = factory(Product::class)->create();

        $product->categories()->save(
            $category = factory(Category::class)->create()
        );

        $this->assertEquals(1, $product->categories()->count());
        $this->assertTrue($product->categories()->first()->is($category));
    }

    /** @test */
    function a_product_can_have_many_variations()
    {
        $product = factory(Product::class)->create();

        $product->variations()->save(
            $variation = factory(ProductVariation::class)->create()
        );

        $this->assertEquals(1, $product->variations()->count());
        $this->assertTrue($product->variations()->first()->is($variation));
    }

    /** @test */
    function the_price_of_a_product_is_a_money_instance()
    {
        $product = factory(Product::class)->create();

        $this->assertInstanceOf(Money::class, $product->price);
    }

    /** @test */
    function a_product_has_a_formatted_price()
    {
        $product = factory(Product::class)->create(['price' => 150]);

        $this->assertEquals('$1.50', $product->formattedPrice);
    }
}
