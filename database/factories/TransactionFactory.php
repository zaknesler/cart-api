<?php

use App\Models\Order;
use App\Models\Transaction;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Transaction::class, function (Faker $faker) {
    return [
        'order_id' => factory(Order::class),
        'total' => 1000,
    ];
});
