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
Route::namespace('Submissao')->group(function () {
    Route::get('/detalhesTrabalho','TrabalhoController@detalhesAjax')->name('detalhesTrabalho');

});
Route::namespace('Users')->group(function () {
    Route::get('/numeroRevisoresPorArea','RevisorController@numeroDeRevisoresAjax')->name('numeroDeRevisoresAjax');

});

