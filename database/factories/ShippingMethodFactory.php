<?php

use Faker\Generator as Faker;
use App\Models\ShippingMethod;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(ShippingMethod::class, function (Faker $faker) {
    return [
        'name' => 'U.S. Mail',
        'price' => 1000,
    ];
});
