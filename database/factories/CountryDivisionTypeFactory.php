<?php

use Faker\Generator as Faker;
use App\Models\CountryDivisionType;

$factory->define(CountryDivisionType::class, function (Faker $faker) {
    return [
        'name' => 'State',
    ];
});
