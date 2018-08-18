<?php

use Faker\Generator as Faker;

$factory->define(App\Models\ProductVariation::class, function (Faker $faker) {
    return [
        'product_id' => function () {
            return factory(App\Models\Product::class)->create()->id;
        },
        'name' => $faker->sentence(3),
        'price' => $faker->numberBetween(1000, 10000),
    ];
});
