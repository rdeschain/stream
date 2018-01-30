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

Route::get('/', function () {
    return view('testauth');
});

Route::group([
    'middleware' => [
        'web'
    ]
], function () {

    Route::post('callback', 'GoogleOAuthController@callback');
    Route::get('user', 'GoogleOAuthController@viewuser');

    Route::post('list/{id}', 'LiveChatMessageController@getMessage');
    Route::get('list/{id}', 'LiveChatMessageController@retrieveMessage');
    Route::post('chat/{id}', 'LiveChatMessageController@postMessage');

    Route::get('video/list', 'VideoController@getVideoSearch');
    Route::get('video/{id}', 'VideoController@getVideoStream');

});