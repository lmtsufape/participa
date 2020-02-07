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
    return view('index');
});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// rotas de teste
Route::get('/coordenador/home',function(){
    return view('coordenador.home');
})->name('coord.home');

Route::get('/coordenador/evento/detalhes',function(){
    return view('coordenador.detalhesEvento');
})->name('coord.detalhesEvento');
