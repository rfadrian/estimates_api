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

Route::get('/estimate', 'EstimateController@index');

Route::post('/estimate', 'EstimateController@store');

//Usando estimate como parametro, Se asigna directamente el modelo a la ruta.
Route::post('/estimate/{estimate}', 'EstimateController@update');

Route::post('/estimate/publish/{estimate}', 'EstimateController@publish');

Route::post('/estimate/discard/{estimate}', 'EstimateController@discard');
