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
use App\Evento;
Route::get('/', function () {
    if(Auth::check()){
      return redirect()->route('home');
    }

    $eventos = Evento::all();
    return view('index',['eventos'=>$eventos]);
});

Route::get('/#', function () {
    if(Auth::check()){
      return redirect()->route('home');
    }

    $eventos = Evento::all();
    return view('index',['eventos'=>$eventos]);
})->name('cancelarCadastro');

  Route::get('/evento/visualizar/naologado/{id}','EventoController@showNaoLogado')->name('evento.visualizarNaoLogado');

Auth::routes(['verify' => true]);

Route::get('/perfil','UserController@perfil')->name('perfil')->middleware('auth');
Route::post('/perfil','UserController@editarPerfil')->name('perfil')->middleware('auth');

Route::group(['middleware' => ['isTemp', 'auth', 'verified']], function(){

  Route::get('/home', 'EventoController@index')->name('home');

  // rotas de teste
  Route::get('/coordenador/home','EventoController@index')->name('coord.home');

  Route::get('/coord/evento/detalhes', 'EventoController@informacoes')->name('coord.detalhesEvento');
  Route::get('/coord/evento/informacoes', 'EventoController@informacoes')->name('coord.informacoes');
  Route::get('/coord/evento/definirSubmissoes', 'EventoController@definirSubmissoes')->name('coord.definirSubmissoes');
  Route::get('/coord/evento/listarTrabalhos', 'EventoController@listarTrabalhos')->name('coord.listarTrabalhos');
  Route::get('/coord/evento/cadastrarComissao', 'EventoController@cadastrarComissao')->name('coord.cadastrarComissao');
  Route::get('/coord/evento/cadastrarAreas', 'EventoController@cadastrarAreas')->name('coord.cadastrarAreas');
  Route::get('/coord/evento/listarAreas', 'EventoController@listarAreas')->name('coord.listarAreas');
  Route::get('/coord/evento/cadastrarRevisores', 'EventoController@cadastrarRevisores')->name('coord.cadastrarRevisores');
  Route::get('/coord/evento/listarRevisores', 'EventoController@listarRevisores')->name('coord.listarRevisores');
  Route::get('/coord/evento/definirCoordComissao', 'EventoController@definirCoordComissao')->name('coord.definirCoordComissao');
  Route::get('/coord/evento/listarComissao', 'EventoController@listarComissao')->name('coord.listarComissao');
  Route::get('/coord/evento/cadastrarModalidade', 'EventoController@cadastrarModalidade')->name('coord.cadastrarModalidade');
  Route::get('/coord/evento/listarModalidade', 'EventoController@listarModalidade')->name('coord.listarModalidade');
  Route::get('/coord/evento/cadastrarCriterio', 'EventoController@cadastrarCriterio')->name('coord.cadastrarCriterio');
  Route::get('/coord/evento/listarCriterios', 'EventoController@listarCriterios')->name('coord.listarCriterios');
  Route::get('/coord/evento/editarEtiqueta', 'EventoController@editarEtiqueta')->name('coord.editarEtiqueta');
  Route::get('/coord/evento/etiquetasTrabalhos', 'EventoController@etiquetasTrabalhos')->name('coord.etiquetasTrabalhos');

  // Visualizar trabalhos do usuário
  Route::get('/user/trabalhos', 'UserController@meusTrabalhos')->name('user.meusTrabalhos');

  // Cadastrar Comissão
  Route::post('/evento/cadastrarComissao','ComissaoController@store'                   )->name('cadastrar.comissao');
  Route::post('/evento/cadastrarCoordComissao','ComissaoController@coordenadorComissao')->name('cadastrar.coordComissao');
  //Evento
  Route::get(   '/evento/criar',          'EventoController@create'                    )->name('evento.criar');
  Route::post(  '/evento/criar',          'EventoController@store'                     )->name('evento.criar');
  Route::get(   '/evento/visualizar/{id}','EventoController@show'                      )->name('evento.visualizar');
  Route::delete('/evento/excluir/{id}',   'EventoController@destroy'                   )->name('evento.deletar');
  Route::get(   '/evento/editar/{id}',    'EventoController@edit'                      )->name('evento.editar');
  Route::post(   '/evento/editar/{id}',    'EventoController@update'                   )->name('evento.update');
  Route::post(  '/evento/setResumo',      'EventoController@setResumo'                 )->name('evento.setResumo');
  Route::post(  '/evento/setFoto',        'EventoController@setFotoEvento'             )->name('evento.setFotoEvento');
  Route::post(  '/evento/numTrabalhos',   'EventoController@numTrabalhos'             )->name('trabalho.numTrabalhos');
  Route::get(  '/evento/habilitar/{id}',  'EventoController@habilitar'                )->name('evento.habilitar');
  Route::get(  '/evento/desabilitar/{id}', 'EventoController@desabilitar'             )->name('evento.desabilitar');
  //Modalidade
  Route::post(  '/modalidade/criar',      'ModalidadeController@store'                 )->name('modalidade.store');
  //Area
  Route::post(  '/area/criar',            'AreaController@store'                       )->name('area.store');
  //Revisores
  Route::post(  '/revisor/criar',         'RevisorController@store'                    )->name('revisor.store');
  Route::get(   '/revisor/listarTrabalhos','RevisorController@indexListarTrabalhos'    )->name('revisor.listarTrabalhos');
  Route::post(  '/revisor/email',         'RevisorController@enviarEmailRevisor'       )->name('revisor.email');
  Route::post(  '/revisor/emailTodos',    'RevisorController@enviarEmailTodosRevisores')->name('revisor.emailTodos');
  //AreaModalidade
  Route::post(  '/areaModalidade/criar',  'AreaModalidadeController@store'             )->name('areaModalidade.store');
  //Trabalho
  Route::get(   '/trabalho/submeter/{id}/{idModalidade}','TrabalhoController@index'                   )->name('trabalho.index');
  Route::post(  '/trabalho/novaVersao',   'TrabalhoController@novaVersao'              )->name('trabalho.novaVersao');
  Route::post(  '/trabalho/criar/{id}}',        'TrabalhoController@store'                   )->name('trabalho.store');
  //Atribuição
  Route::get(   '/atribuir',              'AtribuicaoController@distribuicaoAutomatica')->name('distribuicao');
  Route::get(   '/atribuirPorArea',       'AtribuicaoController@distribuicaoPorArea'   )->name('distribuicaoAutomaticaPorArea');
  Route::post(  '/distribuicaoManual',    'AtribuicaoController@distribuicaoManual'    )->name('distribuicaoManual');
  Route::post(  '/removerAtribuicao',     'AtribuicaoController@deletePorRevisores'    )->name('atribuicao.delete');
  // rota downloadArquivo
  Route::get(   '/downloadArquivo',       'HomeController@downloadArquivo'             )->name('download');
  // rota download do arquivo do trabalho
  Route::get(   '/download-trabalho/{id}',     'TrabalhoController@downloadArquivo'         )->name('downloadTrabalho');
  // rota download da foto do evento
  Route::get(   '/download-logo-evento/{id}',   'EventoController@downloadFotoEvento'  )->name('download.foto.evento');
  // rota download arquivo de regras para submissão de trabalho
  Route::get(   '/downloadArquivoRegras',       'RegraSubmisController@downloadArquivo')->name('download.regra');
  // rota download arquivo de templates para submissão de trabalho
  Route::get(   '/downloadArquivoTemplates',    'TemplateSubmisController@downloadArquivo'       )->name('download.template');
  // atualizar etiquetas do form de eventos
  Route::post(  '/etiquetas/editar/{id}', 'FormEventoController@update'                )->name('etiquetas.update');
  // atualizar etiquetas do form de submissão de trabalhos
  Route::post(  '/etiquetas/submissao_trabalhos/editar/{id}', 'FormSubmTrabaController@update')->name('etiquetas_sub_trabalho.update');
  // Inserir novos campos para o form de submissão de trabalhos
  Route::post(  '/adicionarnovocampo/{id}', 'FormSubmTrabaController@store'            )->name('novocampo.store');
  // Exibir ou ocultar modulos do card de eventos
  Route::post(  '/modulos/{id}', 'FormEventoController@exibirModulo'                   )->name('exibir.modulo');
  // Ajax para encontrar modalidade especifica e enviar para o modal de edição
  Route::get(   '/encontrarModalidade',   'ModalidadeController@find'                  )->name('findModalidade');
  // Ajax para encontrar modalidade especifica e enviar para o modal de edição
  Route::post(   '/atualizarModalidade',   'ModalidadeController@update'                )->name('modalidade.update');
  //
  Route::get(    '/area/revisores',        'RevisorController@indexListarTrabalhos'     )->name('avaliar.trabalhos');
  // Encontrar resumo especifico para trabalhos
  Route::get(   '/encontrarResumo',    'TrabalhoController@findResumo'                  )->name('trabalhoResumo');
  // Critérios
  Route::post(  '/criterio/', 'CriteriosController@store'                               )->name('cadastrar.criterio');
  Route::post(  '/criterioUpdate/', 'CriteriosController@update'                        )->name('atualizar.criterio');
  Route::get(   '/encontrarCriterio', 'CriteriosController@findCriterio'                )->name('encontrar.criterio');
});
