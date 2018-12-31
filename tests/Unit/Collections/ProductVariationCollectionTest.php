<?php

namespace Tests\Unit\Collections;

use Tests\TestCase;
use App\Models\User;
use App\Models\ProductVariation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Collections\ProductVariationCollection;

class ProductVariationCollectionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_product_variation_collection_returns_a_syncing_array()
    {
        $user = factory(User::class)->create();

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create(), [
                'quantity' => 2,
            ]
        );

        $collection = new ProductVariationCollection($user->cart);

        $this->assertEquals([
            1 => [
                'quantity' => 2,
            ],
        ], $collection->forSyncing());
    }
}
