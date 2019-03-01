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

Route::get('/get_categoria/{id?}','CategoriaController@get');
Route::get('/get_totais_categoria_produtos','CategoriaController@totaisCategoriasProdutos');
Route::post('/delete_categoria','CategoriaController@destroy');
Route::post('/criar_categoria','CategoriaController@create');
