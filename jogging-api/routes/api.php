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

    Route::get('/users', 'UserController@getAll')->middleware('can:viewAll,App\Models\User');
    Route::delete('/users/{user}', 'UserController@deleteOne')->middleware('can:delete,user');
    Route::put('/users/{user}', 'UserController@putOne')->middleware('can:edit,user');
    Route::get('/users/{user}', 'UserController@getOne')->middleware('can:view,user');

    Route::get('/users/{user}/jogs', 'UserController@getJogs');

    Route::delete('/jogs/{jog}', 'JogController@deleteOne')->middleware('can:delete,jog');
    Route::get('/jogs/{jog}', 'JogController@getOne')->middleware('can:view,jog');
    Route::post('/jogs', 'JogController@postOne')->middleware('can:create,App\Models\Jog');
    Route::put('/jogs/{jog}', 'JogController@putOne')->middleware('can:edit,jog');
    Route::get('/jogs', 'JogController@getAll')->middleware('can:viewAll,App\Models\Jog');
});
