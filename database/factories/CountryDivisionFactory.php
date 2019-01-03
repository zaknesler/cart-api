<?php

use App\Models\Country;
use Faker\Generator as Faker;
use App\Models\CountryDivision;
use App\Models\CountryDivisionType;

$factory->define(CountryDivision::class, function (Faker $faker) {
    return [
        'country_id' => function () {
            return factory(Country::class)->create()->id;
        },
        'country_division_type_id' => function () {
            return factory(CountryDivisionType::class)->create()->id;
        },
        'name' => 'Some Division',
        'code' => 'SD',
    ];
});
