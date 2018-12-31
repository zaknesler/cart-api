<?php

use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use Faker\Generator as Faker;
use App\Models\ShippingMethod;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'address_id' => function () {
            return factory(Address::class)->create()->id;
        },
        'shipping_method_id' => function () {
            return factory(ShippingMethod::class)->create()->id;
        },
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'status' => 'pending',
    ];
});
