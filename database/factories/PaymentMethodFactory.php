<?php

use App\Models\User;
use App\Models\PaymentMethod;
use Faker\Generator as Faker;

$factory->define(PaymentMethod::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'card_type' => 'Visa',
        'last_four' => '4242',
        'default' => true,
        'provider_id' => 'tok_fake_' . str_random(10),
    ];
});
