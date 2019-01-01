<?php

use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use App\Models\PaymentMethod;
use Faker\Generator as Faker;
use App\Models\ShippingMethod;

$factory->define(Order::class, function (Faker $faker) {
    $user = factory(User::class)->create();

    return [
        'address_id' => function () {
            return factory(Address::class)->create()->id;
        },
        'shipping_method_id' => function () {
            return factory(ShippingMethod::class)->create()->id;
        },
        'user_id' => function () use ($user) {
            return $user->id;
        },
        'payment_method_id' => function () use ($user) {
            return factory(PaymentMethod::class)->create(['user_id' => $user->id])->id;
        },
        'status' => 'pending',
        'subtotal' => 0,
    ];
});
