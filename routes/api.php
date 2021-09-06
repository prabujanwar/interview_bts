<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::post('users/signin', 'JWTAuthController@login');
Route::post('users/signup', 'JWTAuthController@register');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('users', 'JWTAuthController@getUsers');
    Route::post('shopping', 'ShoppingController@create');
    Route::get('shopping', 'ShoppingController@fetch');
    Route::get('shopping/{id}', 'ShoppingController@fetch');
    Route::post('shopping/{id}', 'ShoppingController@update');
    Route::delete('shopping/{id}', 'ShoppingController@delete');

    Route::post('logout', 'JWTAuthController@logout');
});
