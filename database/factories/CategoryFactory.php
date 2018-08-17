<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Category::class, function (Faker $faker) {
    return [
        'name' => $name = $faker->unique()->sentence(2),
        'slug' => str_slug($name),
    ];
});
