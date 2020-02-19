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

Route::get('/coordenador/evento/detalhes', 'EventoController@detalhes')->name('coord.detalhesEvento');

Route::get('/perfil','UserController@perfil')->name('perfil');
Route::post('/perfil','UserController@editarPerfil')->name('perfil');


// Cadastrar Comissão
Route::post('/evento/cadastrarComissao','ComissaoController@store')->name('cadastrar.comissao');
Route::post('/evento/cadastrarCoordComissao','ComissaoController@coordenadorComissao')->name('cadastrar.coordComissao');


//Evento
Route::get(   '/evento/criar',          'EventoController@create'               )->name('evento.criar');
Route::post(  '/evento/criar',          'EventoController@store'                )->name('evento.criar');
Route::delete('/evento/excluir/{id}',   'EventoController@destroy'              )->name('evento.deletar');
Route::get(   '/evento/editar/{id}',    'EventoController@edit'                 )->name('evento.editar');
Route::post(  '/evento/editar/{id}',    'EventoController@update'               )->name('evento.editar');
//Modalidade
Route::post(  '/modalidade/criar',      'ModalidadeController@store'            )->name('modalidade.store');
//Area
Route::post(  '/area/criar',            'AreaController@store'                  )->name('area.store');
//Revisores
Route::post(  '/revisor/criar',         'RevisorController@store'               )->name('revisor.store');
//AreaModalidade
Route::post(  '/areaModalidade/criar',  'AreaModalidadeController@store'        )->name('areaModalidade.store');
//Trabalho
Route::post(  '/trabalho/criar',        'TrabalhoController@store'              )->name('trabalho.store');
