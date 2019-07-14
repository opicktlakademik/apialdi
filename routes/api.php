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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('index', function () {
//     echo "Hi, you are here";
// });

// //handle from phone
// Route::put('open/{apikey}', 'ServiceController@open');
// Route::put('close/{apikey}', 'ServiceController@close');
// Route::put('lock/{apikey}', 'ServiceController@lock');
// Route::put('unlock/{apikey}', 'ServiceController@unlock');
// Route::get('check/{apikey}', 'ServiceController@check');

// //handle wemos request
// Route::post('create', 'ServiceController@createWemos');
// Route::put('update', 'ServiceController@updateWemos');

Route::resource('photos', 'PhotoControllerApi')->middleware('auth:api');
