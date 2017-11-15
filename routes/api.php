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

Route::get('/estimate/{page?}/{email?}', 'EstimateController@index');

Route::post('/estimate', 'EstimateController@store');

//Using estimate as parameter name, it is automatically binded to the controller. app\Exceptions\Handler has a method to override the default laravel exception on model not found.
Route::post('/estimate/{estimate}', 'EstimateController@update');

Route::post('/estimate/publish/{estimate}', 'EstimateController@publish');

Route::post('/estimate/discard/{estimate}', 'EstimateController@discard');
