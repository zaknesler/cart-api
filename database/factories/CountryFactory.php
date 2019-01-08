<?php

use App\Models\Country;
use Faker\Generator as Faker;

$factory->define(Country::class, function (Faker $faker) {
    return [
        'code' => 'US',
        'name' => 'United States',
        'has_divisions' => false,
        'division_type' => null,
    ];
});
