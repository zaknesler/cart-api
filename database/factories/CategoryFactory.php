<?php

use App\Models\Category;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $name = $faker->unique()->sentence(2),
        'slug' => str_slug($name),
    ];
});
