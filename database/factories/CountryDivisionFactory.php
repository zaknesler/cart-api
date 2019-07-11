<?php

use App\Models\Country;
use Faker\Generator as Faker;
use App\Models\CountryDivision;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(CountryDivision::class, function (Faker $faker) {
    return [
        'country_id' => factory(Country::class),
        'name' => 'Some Division',
        'code' => 'SD',
    ];
});
