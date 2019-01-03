<?php

use App\Models\User;
use App\Models\Address;
use App\Models\Country;
use Faker\Generator as Faker;
use App\Models\CountryDivision;

$factory->define(Address::class, function (Faker $faker) {
    $country = factory(Country::class)->create();

    return [
        'name' => $faker->name,
        'address_1' => $faker->streetAddress,
        'address_2' => $faker->secondaryAddress,
        'city' => $faker->city,
        'postal_code' => $faker->postcode,
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'country_id' => function () use ($country) {
            return $country->id;
        },
        'country_division_id' => function () use ($country) {
            return factory(CountryDivision::class)->create([
                'country_id' => $country->id,
            ])->id;
        },
        'default' => false,
    ];
});
