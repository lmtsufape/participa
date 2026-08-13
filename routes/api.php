<?php

use App\Http\Controllers\Inscricao\CheckoutController;
use App\Http\Controllers\Submissao\TrabalhoController;
use App\Http\Controllers\Users\RevisorController;
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

Route::get('/detalhesTrabalho', [TrabalhoController::class, 'detalhesAjax'])->name('detalhesTrabalho');
Route::get('/numeroRevisoresPorArea', [RevisorController::class, 'numeroDeRevisoresAjax'])->name('numeroDeRevisoresAjax');

Route::get('/credenciamento/inscrito', [InscricaoApiController::class, 'buscarInscritoPorDocumento'])->middleware('apiInscricaoAba');

Route::post('/checkout/notifications', [CheckoutController::class, 'notifications'])->name('checkout.notifications');
