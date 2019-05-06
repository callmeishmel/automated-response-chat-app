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

Route::get('/logout' , 'Auth\LoginController@logout');

Route::get('/', 'ChatsController@index');
Route::get('messages/{contactId?}', 'ChatsController@fetchMessages');
Route::post('messages', 'ChatsController@sendMessage');

Route::get('current-app-user', 'ChatsController@getCurrentAppUser');
Route::get('user-contacts', 'ChatsController@getUserContacts');
Route::get('add-contact-notification/{contactId}', 'ChatsController@addContactNotification');
Route::get('remove-contact-notification/{contactId}', 'ChatsController@removeContactNotification');

Route::get('canned-message-responses/{id?}', 'ChatsController@getCannedMessageResponses');

Route::get('users', 'AdminController@createUserPage');
Route::post('users', 'AdminController@createUserPost');

Route::get('delete-user/{userId}', 'AdminController@deleteUserPage');
Route::post('delete-user/{userId}', 'AdminController@deleteUserPagePost');

Route::get('canned-messages', 'AdminController@cannedMessagesPage');
Route::post('canned-messages', 'AdminController@cannedMessagesPagePost');

Route::get('delete-canned-message/{cannedMessageId}', 'AdminController@deleteCannedMessagePage');
Route::post('delete-canned-message/{cannedMessageId}', 'AdminController@deleteCannedMessagePagePost');

Route::post('pusher/presence-webhook', 'PusherWebhookController@presenceWebhook');

Route::get('pusher/api-test', 'PusherWebhookController@pusherAPICall');
