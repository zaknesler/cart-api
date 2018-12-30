<?php

Route::apiResource('/categories', 'CategoryController');
Route::apiResource('/products', 'ProductController');

Route::prefix('/auth')->namespace('Auth')->group(function () {
    Route::post('/register', 'RegisterController@store');
    Route::post('/login', 'LoginController@store');

    Route::get('/me', 'MeController@view');
});

Route::prefix('/cart')->namespace('Cart')->group(function () {
    Route::post('/', 'CartController@store');
    Route::patch('/{productVariation}', 'CartController@update');
    Route::delete('/{productVariation}', 'CartController@destroy');
});
