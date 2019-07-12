<?php

/**
 * Cart
 */
Route::prefix('/cart')->group(function () {
    Route::get('/', 'CartController@index');
    Route::post('/', 'CartController@store');
    Route::patch('/{productVariation}', 'CartController@update');
    Route::delete('/{productVariation}', 'CartController@destroy');
});

/**
 * Addresses
 */
Route::prefix('/addresses')->group(function () {
    Route::get('/', 'AddressController@index');
    Route::post('/', 'AddressController@store');
    Route::delete('/{address}', 'AddressController@destroy');

    Route::get('/{address}/shipping', 'AddressShippingController@index');
});

/**
 * Orders
 */
Route::prefix('/orders')->group(function () {
    Route::get('/', 'OrderController@index');
    Route::post('/', 'OrderController@store');
});

/**
 * Payment Methods
 */
Route::prefix('/payment-methods')->group(function () {
    Route::get('/', 'PaymentMethodController@index');
    Route::post('/', 'PaymentMethodController@store');
    Route::delete('/{paymentMethod}', 'PaymentMethodController@destroy');
});
