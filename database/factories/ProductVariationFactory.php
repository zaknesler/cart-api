<?php

use App\Models\Product;
use Faker\Generator as Faker;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;

$factory->define(ProductVariation::class, function (Faker $faker) {
    return [
        'product_id' => function () {
            return factory(Product::class)->create()->id;
        },
        'name' => $faker->sentence(3),
        'price' => $faker->numberBetween(1000, 10000),
        'product_variation_type_id' => function () {
            return factory(ProductVariationType::class)->create()->id;
        },
    ];
});

$factory->state(App\Models\ProductVariation::class, 'stocked', function (Faker $faker) {
    return [
        //
    ];
});

$factory->afterCreatingState(App\Models\ProductVariation::class, 'stocked', function ($variation, Faker $faker) {
    factory(\App\Models\Stock::class)->create([
        'product_variation_id' => $variation->id,
        'quantity' => 50,
    ]);
});
