<?php

use App\Models\Stock;
use Faker\Generator as Faker;
use App\Models\ProductVariation;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Stock::class, function (Faker $faker) {
    return [
        'quantity' => 1,
        'product_variation_id' => factory(ProductVariation::class),
    ];
});
