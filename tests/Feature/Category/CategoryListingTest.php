<?php

namespace Tests\Feature\Category;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryListingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function categories_can_be_listed()
    {
        $categories = factory(Category::class, 3)->create();

        $response = $this->get('/api/categories');

        $response->assertSuccessful();
        $categories->each(function ($category) use ($response) {
            $response->assertJsonFragment([
                'name' => $category->name,
                'slug' => $category->slug,
            ]);
        });
    }

    /** @test */
    function only_parent_categories_are_listed()
    {
        $categoryA = factory(Category::class)->create();
        $categoryB = factory(Category::class)->create([ 'parent_id' => 1 ]);

        $response = $this->get('/api/categories');

        $response->assertSuccessful();
        $response->assertJsonCount(1, 'data');
    }

    /** @test */
    function categories_with_children_can_be_listed()
    {
        $categoryA = factory(Category::class)->create();
        $categoryB = factory(Category::class)->create([ 'parent_id' => 1 ]);

        $response = $this->get('/api/categories');

        $response->assertSuccessful();
        $response->assertJsonFragment([
            'name' => $categoryA->name,
            'slug' => $categoryA->slug,
            'children' => [
                [
                    'name' => $categoryB->name,
                    'slug' => $categoryB->slug,
                ]
            ],
        ]);
    }

    /** @test */
    function listed_categories_are_ordered_properly()
    {
        $categoryA = factory(Category::class)->create([ 'order' => 2 ]);
        $categoryB = factory(Category::class)->create([ 'order' => 1 ]);

        $response = $this->get('/api/categories');

        $response->assertSuccessful();
        $response->assertSeeInOrder([
            $categoryB->name,
            $categoryA->name,
        ]);
    }
}
