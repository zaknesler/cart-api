<?php

namespace Tests\Unit\Models;

use App\Cart\Money;
use Tests\TestCase;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductVariationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_product_variation_has_one_type()
    {
        $variation = factory(ProductVariation::class)->create();

        $this->assertInstanceOf(ProductVariationType::class, $variation->type);
    }

    /** @test */
    function a_product_variation_belongs_to_a_product()
    {
        $variation = factory(ProductVariation::class)->create();

        $this->assertInstanceOf(Product::class, $variation->product);
    }

    /** @test */
    function the_price_of_a_product_variation_is_a_money_instance()
    {
        $variation = factory(ProductVariation::class)->create();

        $this->assertInstanceOf(Money::class, $variation->price);
    }

    /** @test */
    function a_product_variation_has_a_formatted_price()
    {
        $variation = factory(ProductVariation::class)->create(['price' => 150]);

        $this->assertEquals('$1.50', $variation->formattedPrice);
    }

    /** @test */
    function a_product_returns_the_parent_product_price_if_it_has_no_price()
    {
        $product = factory(Product::class)->create(['price' => 150]);
        $variation = factory(ProductVariation::class)->create(['price' => null, 'product_id' => $product->id]);

        $this->assertEquals($product->price->amount(), $variation->price->amount());
    }

    /** @test */
    function a_product_variation_can_check_if_its_price_is_different_than_its_parent_product()
    {
        $product = factory(Product::class)->create(['price' => 200]);
        $variation = factory(ProductVariation::class)->create(['price' => 100, 'product_id' => $product->id]);

        $this->assertTrue($variation->priceVaries());
    }
}
