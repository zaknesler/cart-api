<?php

use Faker\Generator as Faker;
use App\Models\ProductVariationType;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(ProductVariationType::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(2),
    ];
});
