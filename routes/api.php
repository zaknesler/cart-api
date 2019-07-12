<?php

/**
 * Authentication
 */
Route::prefix('/auth')->namespace('Auth')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::delete('/logout', 'LogoutController@destroy');

        Route::get('/me', 'MeController@view');
    });

    Route::post('/register', 'RegisterController@store');
    Route::post('/login', 'LoginController@store');
});

/**
 * Categories
 */
Route::prefix('/categories')->group(function () {
    Route::get('/', 'CategoryController@index');
});

/**
 * Products
 */
Route::prefix('/products')->group(function () {
    Route::get('/', 'ProductController@index');
    Route::get('/{product}', 'ProductController@show');
});

/**
 * Countries
 */
Route::prefix('/countries')->group(function () {
    Route::get('/', 'CountryController@index');
});
