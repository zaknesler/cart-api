<?php

Route::apiResource('/categories', 'CategoryController');
Route::apiResource('/products', 'ProductController');

Route::prefix('/auth')->namespace('Auth')->group(function () {
    Route::post('/register', 'RegisterController@store');
});
