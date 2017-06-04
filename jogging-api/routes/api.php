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

Route::get('/', function () {
    return response()->ok('Jogging API');
});

Route::post('/users', 'UserController@postOne');
Route::post('/users/login', 'UserController@login');

Route::group([
    'middleware' => 'jwtAuth',
], function () {
    Route::get('/me', function () {
        return response()->ok(Auth::user());
    });

    Route::get('/users/{user}/jogs', 'UserController@getJogs');

    Route::delete('/jogs/{jog}', 'JogController@deleteOne');
    Route::get('/jogs/{jog}', 'JogController@getOne');
    Route::post('/jogs', 'JogController@postOne');
    Route::put('/jogs/{jog}', 'JogController@putOne');
});
