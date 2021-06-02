<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return view('home');
});

Route::get('/pusher', function () {
    event(new App\Events\TestPusherEvent('Hi there Pusher!'));
    return "Event has been sent!";
});

Route::get('/fetchdata', function () {
    event(new App\Events\FetchDataEvent('Hi there Pusher!'));
    return "Event has been sent!";
});
