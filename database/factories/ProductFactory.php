<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Product::class, function (Faker $faker) {
    return [
        'name' => $name = $faker->unique()->sentence(3),
        'slug' => str_slug($name),
        'description' => $faker->paragraph,
        'price' => $faker->numberBetween(1000, 10000),
    ];
});
