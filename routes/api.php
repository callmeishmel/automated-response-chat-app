<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->get('contacts-online-status', 'ChatsController@getUserContactsOnlineStatus');

Route::middleware('auth:api')->put('user/{user}/online', 'UserOnlineController');

Route::middleware('auth:api')->put('user/{user}/offline', 'UserOfflineController');
