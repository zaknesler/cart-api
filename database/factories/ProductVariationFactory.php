<?php

use App\Models\Product;
use Faker\Generator as Faker;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(ProductVariation::class, function (Faker $faker) {
    return [
        'product_id' => factory(Product::class),
        'name' => $faker->sentence(3),
        'price' => $faker->numberBetween(1000, 10000),
        'product_variation_type_id' => factory(ProductVariationType::class),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->state(App\Models\ProductVariation::class, 'stocked', function (Faker $faker) {
    return [
        //
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->afterCreatingState(App\Models\ProductVariation::class, 'stocked', function ($variation, Faker $faker) {
    factory(\App\Models\Stock::class)->create([
        'product_variation_id' => $variation->id,
        'quantity' => 50,
    ]);
});
