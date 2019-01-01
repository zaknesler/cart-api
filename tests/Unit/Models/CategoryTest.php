<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_category_can_have_many_children()
    {
        $categoryA = factory(Category::class)->create();

        $categoryA->children()->save(
            $categoryB = factory(Category::class)->create()
        );

        $this->assertEquals($categoryB->id, $categoryA->children()->first()->id);
        $this->assertEquals($categoryA->id, $categoryB->parent_id);
        $this->assertTrue($categoryA->children()->first()->is($categoryB));
    }

    /** @test */
    function categories_can_be_sorted_by_only_parents()
    {
        $categoryA = factory(Category::class)->create();

        $categoryA->children()->save(
            $categoryB = factory(Category::class)->create()
        );

        $this->assertEquals(1, Category::parents()->count());
    }

    /** @test */
    function categories_can_be_ordered()
    {
        $categoryA = factory(Category::class)->create(['order' => 2]);
        $categoryB = factory(Category::class)->create(['order' => 1]);

        $this->assertEquals($categoryB->id, Category::ordered()->first()->id);
    }

    /** @test */
    function a_category_can_have_many_products()
    {
        $category = factory(Category::class)->create();

        $category->products()->save(
            $product = factory(Product::class)->create()
        );

        $this->assertEquals(1, $category->products()->count());
        $this->assertInstanceOf(Product::class, $category->products()->first());
        $this->assertTrue($category->products()->first()->is($product));
    }
}
