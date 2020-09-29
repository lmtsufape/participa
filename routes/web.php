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
Route::get('/', 'HomeController@index')->name('home');

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

  // rotas do administrador
  Route::get('admin/home', 'AdministradorController@index')->name('admin.home');
  Route::get('admin/editais', 'AdministradorController@editais')->name('admin.editais');
  Route::get('admin/areas', 'AdministradorController@areas')->name('admin.areas');
  Route::get('admin/usuarios', 'AdministradorController@usuarios')->name('admin.usuarios');
  // rotas da Comissao Cientifica
  Route::get('comissaoCientifica/home', 'CoordComissaoCientificaController@index')->name('cientifica.home');
  Route::get('comissaoCientifica/editais', 'CoordComissaoCientificaController@index')->name('cientifica.editais');
  Route::get('comissaoCientifica/usuarios', 'CoordComissaoCientificaController@usuarios')->name('cientifica.usuarios');
  Route::get('comissaoCientifica/areas', 'CoordComissaoCientificaController@index')->name('cientifica.areas');
  Route::post('comissaoCientifica/permissoes', 'CoordComissaoCientificaController@permissoes')->name('cientifica.permissoes');

  // rotas do Comissao Organizadora
  Route::get('/home/comissaoOrganizadora', 'CoordComissaoOrganizadoraController@index')->name('home.organizadora');
  // rotas do Membro da Comissao
  Route::get('/home/membroComissao', 'MembroComissaoController@index')->name('home.membro');

  // rotas de teste
  Route::get('/coordenador/home','EventoController@index')->name('coord.home');
  Route::prefix('/coord/evento/')->name('coord.')->group(function(){
      Route::get('detalhes', 'EventoController@informacoes')->name('detalhesEvento');
      Route::get('informacoes', 'EventoController@informacoes')->name('informacoes');
      Route::get('trabalhos/definirSubmissoes', 'EventoController@definirSubmissoes')->name('definirSubmissoes');
      Route::get('trabalhos/listarTrabalhos', 'EventoController@listarTrabalhos')->name('listarTrabalhos');

      Route::get('areas/cadastrarAreas', 'EventoController@cadastrarAreas')->name('cadastrarAreas');
      Route::get('areas/listarAreas', 'EventoController@listarAreas')->name('listarAreas');

      Route::get('revisores/cadastrarRevisores', 'EventoController@cadastrarRevisores')->name('cadastrarRevisores');
      Route::get('revisores/listarRevisores', 'EventoController@listarRevisores')->name('listarRevisores');

      Route::get('comissao/cadastrarComissao', 'EventoController@cadastrarComissao')->name('cadastrarComissao');
      Route::get('comissao/definirCoordComissao', 'EventoController@definirCoordComissao')->name('definirCoordComissao');
      Route::get('comissao/listarComissao', 'EventoController@listarComissao')->name('listarComissao');

      Route::get('modalidade/cadastrarModalidade', 'EventoController@cadastrarModalidade')->name('cadastrarModalidade');
      Route::get('modalidade/listarModalidade', 'EventoController@listarModalidade')->name('listarModalidade');
      Route::get('modalidade/cadastrarCriterio', 'EventoController@cadastrarCriterio')->name('cadastrarCriterio');
      Route::get('modalidade/listarCriterios', 'EventoController@listarCriterios')->name('listarCriterios');
      
      Route::get('eventos/editarEtiqueta', 'EventoController@editarEtiqueta')->name('editarEtiqueta');
      Route::get('eventos/etiquetasTrabalhos', 'EventoController@etiquetasTrabalhos')->name('etiquetasTrabalhos');

  });

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
