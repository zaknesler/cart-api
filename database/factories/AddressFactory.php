<?php

use App\Models\User;
use App\Models\Address;
use App\Models\Country;
use Faker\Generator as Faker;

$factory->define(Address::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'address_1' => $faker->streetAddress,
        'city' => $faker->city,
        'postal_code' => $faker->postcode,
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'country_id' => function () {
            return factory(Country::class)->create()->id;
        },
        'default' => false,
    ];
});
