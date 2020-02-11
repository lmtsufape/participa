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

// rotas de teste
Route::get('/coordenador/home','EventoController@index')->name('coord.home');

Route::get('/coordenador/evento/detalhes',function(){
    return view('coordenador.detalhesEvento');
})->name('coord.detalhesEvento');
Route::get('/coordenador/evento/detalhes',function(){
    return view('coordenador.detalhesEvento');
})->name('coord.detalhesEvento');


Route::get('/perfil','UserController@perfil')->name('perfil');
Route::post('/perfil','UserController@editarPerfil')->name('perfil');

//criar evento
Route::get('/evento/criar','EventoController@create')->name('evento.criar');
Route::post('/evento/criar','EventoController@store')->name('evento.criar');
// excluir evento
Route::delete('/evento/excluir/{id}','EventoController@destroy')->name('evento.deletar');
// editar evento
Route::get('/evento/editar/{id}','EventoController@edit')->name('evento.editar');
Route::post('/evento/editar/{id}','EventoController@update')->name('evento.editar');

