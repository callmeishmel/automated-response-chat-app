<?php

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

Auth::routes();

Route::get('/', 'ChatsController@index');
Route::get('messages/{contactId?}', 'ChatsController@fetchMessages');
Route::post('messages', 'ChatsController@sendMessage');

Route::get('current-app-user', 'ChatsController@getCurrentAppUser');
Route::get('user-contacts', 'ChatsController@getUserContacts');

Route::get('canned-message-responses/{id?}', 'ChatsController@getCannedMessageResponses');
