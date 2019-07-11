<?php

use App\Models\User;
use App\Models\PaymentMethod;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(PaymentMethod::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'card_type' => 'Visa',
        'last_four' => '4242',
        'default' => true,
        'provider_id' => 'tok_fake_' . str_random(10),
    ];
});
