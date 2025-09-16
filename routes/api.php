<?php

use App\Http\Controllers\Inscricao\CheckoutController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InscricaoApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('Submissao')->group(function () {
    Route::get('/detalhesTrabalho', 'TrabalhoController@detalhesAjax')->name('detalhesTrabalho');
});
Route::namespace('Users')->group(function () {
    Route::get('/numeroRevisoresPorArea', 'RevisorController@numeroDeRevisoresAjax')->name('numeroDeRevisoresAjax');
});

Route::get('/credenciamento/inscrito/', [InscricaoApiController::class, 'buscarInscritoPorCpf'])->middleware('apiInscricaoAba');;

Route::post('/checkout/notifications', [CheckoutController::class, 'notifications'])->name('checkout.notifications');
