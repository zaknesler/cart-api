<?php

Route::prefix('/auth')->namespace('Auth')->group(function () {
    Route::post('/register', 'RegisterController@store');
    Route::post('/login', 'LoginController@store');

    Route::get('/me', 'MeController@view');
});

Route::prefix('/categories')->namespace('Categories')->group(function () {
    Route::get('/', 'CategoryController@index');
});

Route::prefix('/products')->namespace('Products')->group(function () {
    Route::get('/', 'ProductController@index');
    Route::get('/{product}', 'ProductController@show');
});

Route::prefix('/cart')->namespace('Cart')->group(function () {
    Route::get('/', 'CartController@index');
    Route::post('/', 'CartController@store');
    Route::patch('/{productVariation}', 'CartController@update');
    Route::delete('/{productVariation}', 'CartController@destroy');
});

Route::prefix('/addresses')->namespace('Addresses')->group(function () {
    Route::get('/', 'AddressController@index');
    Route::post('/', 'AddressController@store');

    Route::get('/{address}/shipping', 'AddressShippingController@index');
});

Route::prefix('/countries')->namespace('Countries')->group(function () {
    Route::get('/', 'CountryController@index');
});

Route::prefix('/orders')->namespace('Orders')->group(function () {
    Route::post('/', 'OrderController@store');
});
