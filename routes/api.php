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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::group([
//     'middleware' => 'api',
//     'prefix' => 'auth',

// ], function ($router) {

//     Route::post('register', 'JWTAuthController@register');
//     Route::post('login', 'JWTAuthController@login');
//     Route::post('logout', 'JWTAuthController@logout');
//     Route::post('refresh', 'JWTAuthController@refresh');
//     $router->get('user', 'UserController@profile');

// });

Route::post('login', 'JWTAuthController@login');
Route::post('register', 'JWTAuthController@register');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('me', 'JWTAuthController@me');
    Route::get('fetch-point', 'JWTAuthController@fetchPoint');
    Route::get('fetch-withdraw', 'JWTAuthController@fetchWithdraw');
    Route::get('fetch-withdraws', 'JWTAuthController@fetchWithdraws');
    Route::post('withdraw-confirm', 'JWTAuthController@confirm');
    Route::post('withdraw-reject', 'JWTAuthController@reject');
    Route::post('point', 'JWTAuthController@point');
    Route::post('tukar', 'JWTAuthController@tukar');
    Route::post('logout', 'JWTAuthController@logout');
});
