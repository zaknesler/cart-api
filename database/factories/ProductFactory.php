<?php

use App\Models\Product;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $name = $faker->unique()->sentence(3),
        'slug' => str_slug($name),
        'description' => $faker->paragraph,
        'price' => $faker->numberBetween(1000, 10000),
    ];
});
