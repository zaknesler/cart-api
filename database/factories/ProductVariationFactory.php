<?php

use App\Models\Product;
use Faker\Generator as Faker;
use App\Models\ProductVariationType;

$factory->define(App\Models\ProductVariation::class, function (Faker $faker) {
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
