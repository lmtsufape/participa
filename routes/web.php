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
use Illuminate\Support\Facades\Log;
use App\Models\Submissao\Evento;

Route::get('/index', 'HomeController@index')->name('index');
Route::get('/evento/busca', 'Submissao\EventoController@buscaLivre')->name('busca.eventos');
Route::get('/evento/buscar-livre', 'Submissao\EventoController@buscaLivreAjax')->name('busca.livre.ajax');




Auth::routes(['verify' => true]);

Route::get('/#', function () {
    if(Auth::check()){
      return redirect()->route('home');
    }

    $eventos = Evento::all();
    return view('index',['eventos'=>$eventos]);
})->name('cancelarCadastro');

Route::namespace('Submissao')->group(function () {
  Route::get('/evento/visualizar/naologado/{id}','EventoController@showNaoLogado')->name('evento.visualizarNaoLogado');
  Route::get('/home', 'EventoController@index')->name('home')->middleware('verified', 'isTemp');

});


Route::get('/{id}/atividades', 'Submissao\AtividadeController@atividadesJson')->name('atividades.json');

Route::get('/perfil','Users\UserController@perfil')->name('perfil')->middleware('auth');
Route::post('/perfil/editar','Users\UserController@editarPerfil')->name('perfil.update')->middleware('auth');


Route::group(['middleware' => ['auth', 'verified', 'isTemp']], function(){
  Route::get('/', 'HomeController@index')->name('home.user');

  Route::namespace('Users')->group(function () {
    // rotas do administrador
    Route::get('admin/home', 'AdministradorController@index')->name('admin.home');
    Route::get('admin/editais', 'AdministradorController@editais')->name('admin.editais');
    Route::get('admin/areas', 'AdministradorController@areas')->name('admin.areas');
    Route::get('admin/usuarios', 'AdministradorController@usuarios')->name('admin.usuarios');
    // rotas da Comissao Cientifica
    Route::get('comissao', 'MembroComissaoController@index')->name('home.membro');
    Route::get('comissaoCientifica/home', 'CoordComissaoCientificaController@index')->name('cientifica.home');
    Route::get('comissaoCientifica/editais', 'CoordComissaoCientificaController@index')->name('cientifica.editais');
    Route::get('comissaoCientifica/areas', 'CoordComissaoCientificaController@index')->name('cientifica.areas');
    Route::post('comissaoCientifica/permissoes', 'CoordComissaoCientificaController@permissoes')->name('cientifica.permissoes');
    Route::post('comissaoCientifica/novoUsuario', 'CoordComissaoCientificaController@novoUsuario')->name('cientifica.novoUsuario');
    // rotas do Comissao Organizadora
    Route::get('/home/comissaoOrganizadora', 'CoordComissaoOrganizadoraController@index')->name('home.organizadora');
    Route::post('comissaoOrganizadora/novoUsuario', 'ComissaoOrganizadoraController@store')->name('cadastrar.comissaoOrganizadora');
    Route::post('comissaoOrganizadora/salvar-coordenador', 'ComissaoOrganizadoraController@salvarCoordenador')->name('comissaoOrganizadora.salvaCoordenador');
    // rotas do Coordenador de evento
    Route::get('/home/coord', 'CoordEventoController@index')->name('coord.index');
    Route::get('/home/coord/eventos', 'CoordEventoController@listaEventos')->name('coord.eventos');
     //Coautor
     Route::get('coautor/index', 'CoautorController@index')->name('coautor.index');
  });

  Route::post('search/user', 'Users\UserController@searchUser')->name('search.user');
  
  // rotas de teste
  Route::namespace('Submissao')->group(function () {
    // Controllers Within The "App\Http\Controllers\Admin" Namespace
    Route::get('/coordenador/home','EventoController@index')->name('coord.home');
    Route::prefix('/coord/evento/')->name('coord.')->group(function(){
      Route::get('detalhes', 'EventoController@informacoes')->name('detalhesEvento');
      Route::get('informacoes', 'EventoController@informacoes')->name('informacoes');
      Route::get('trabalhos/definirSubmissoes', 'EventoController@definirSubmissoes')->name('definirSubmissoes');
      Route::get('trabalhos/listarTrabalhos/{column?}/{direction?}', 'EventoController@listarTrabalhos')->name('listarTrabalhos');
      Route::get('trabalhos/{id}/resultados', 'TrabalhoController@resultados')->name('resultados');

      Route::get('areas/cadastrarAreas', 'EventoController@cadastrarAreas')->name('cadastrarAreas');
      Route::get('areas/listarAreas', 'EventoController@listarAreas')->name('listarAreas');

      Route::get('revisores/cadastrarRevisores', 'EventoController@cadastrarRevisores')->name('cadastrarRevisores');
      
      Route::get('revisores/listarRevisores', 'EventoController@listarRevisores')->name('listarRevisores');
      Route::get('revisores/listarUsuarios', 'EventoController@listarUsuarios')->name('listarUsuarios');

     

      // Route::get('revisores/{id}/disponiveis', 'RevisorController@listarRevisores')->name('adicionarRevisores');

      Route::get('comissaoCientifica/cadastrarComissao', 'EventoController@cadastrarComissao')->name('cadastrarComissao');
      Route::get('comissaoCientifica/definirCoordComissao', 'EventoController@definirCoordComissao')->name('definirCoordComissao');
      Route::get('comissaoCientifica/listarComissao', 'EventoController@listarComissao')->name('listarComissao');
      Route::get('modalidade/cadastrarModalidade', 'EventoController@cadastrarModalidade')->name('cadastrarModalidade');
      Route::get('modalidade/listarModalidade', 'EventoController@listarModalidade')->name('listarModalidade');
      Route::get('modalidade/cadastrarCriterio', 'EventoController@cadastrarCriterio')->name('cadastrarCriterio');
      Route::get('modalidade/listarCriterios', 'EventoController@listarCriterios')->name('listarCriterios');
      Route::get('modalidade/forms', 'EventoController@forms')->name('forms');
      Route::get('modalidade/atribuir/form', 'EventoController@atribuirForm')->name('atribuir.form');
      Route::get('modalidade/form/salvar', 'EventoController@salvarForm')->name('salvar.form');
      Route::get('modalidade/form/visualizar', 'EventoController@visualizarForm')->name('visualizar.form');
      Route::get('modalidade/form/respostas', 'EventoController@respostas')->name('respostas');

      Route::get('atividades/{id}', 'AtividadeController@index')->name('atividades');
      // Atenção se mudar url da rota abaixo mudar função setVisibilidadeAtv na view detalhesEvento.blade.php
      Route::post('atividades/{id}/visibilidade', 'AtividadeController@setVisibilidadeAjax')->name('atividades.visibilidade');
      Route::post('atividade/nova', 'AtividadeController@store')->name('atividades.store');
      Route::post('atividade/{id}/editar', 'AtividadeController@update')->name('atividades.update');
      Route::post('atividade/{id}/excluir', 'AtividadeController@destroy')->name('atividade.destroy');
      Route::post('{id}/atividade/salvar-pdf-programacao', 'EventoController@pdfProgramacao')->name('evento.pdf.programacao');
      Route::get('tipo-de-atividade/new', 'TipoAtividadeController@storeAjax')->name('tipo.store.ajax');
      Route::get('eventos/editarEtiqueta', 'EventoController@editarEtiqueta')->name('editarEtiqueta');
      Route::get('eventos/etiquetasTrabalhos', 'EventoController@etiquetasTrabalhos')->name('etiquetasTrabalhos');
      Route::get('{id}/modulos',              'FormEventoController@indexModulo'            )->name('modulos');
    
    });
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
    Route::post(  '/modalidade/{id}/delete',  'ModalidadeController@destroy'             )->name('modalidade.destroy');
    //Area
    Route::post(  '/area/criar',            'AreaController@store'                       )->name('area.store');
    Route::delete(  '/area/deletar/{id}',   'AreaController@destroy'                     )->name('area.destroy');
    Route::post(    '/area/editar/{id}',    'AreaController@update'                     )->name('area.update'); 

    //AreaModalidade
    // Route::post(  '/areaModalidade/criar',  'AreaModalidadeController@store'             )->name('areaModalidade.store');
    
    //Trabalho
    Route::get(   '/trabalho/submeter/{id}/{idModalidade}','TrabalhoController@index'    )->name('trabalho.index');
    Route::post(  '/trabalho/novaVersao',   'TrabalhoController@novaVersao'              )->name('trabalho.novaVersao');
    Route::post(  '/trabalho/criar/{id}}',        'TrabalhoController@store'             )->name('trabalho.store');
    Route::get(  '/trabalho/pesquisa','TrabalhoController@pesquisaAjax')->name('trabalho.pesquisa.ajax');
    Route::post(  '/trabalho/{id}/avaliar', 'TrabalhoController@avaliarTrabalho')->name('trabalho.avaliacao.revisor');
    //Atribuição
    Route::get(   '/atribuir',              'AtribuicaoController@distribuicaoAutomatica')->name('distribuicao');
    Route::get(   '/atribuirPorArea',       'AtribuicaoController@distribuicaoPorArea'   )->name('distribuicaoAutomaticaPorArea');
    Route::post(  '/distribuicaoManual',    'AtribuicaoController@distribuicaoManual'    )->name('distribuicaoManual');
    Route::post(  '{id}/removerAtribuicao',     'AtribuicaoController@deletePorRevisores')->name('atribuicao.delete');
    Route::post(  '/atribuir/check',                 'AtribuicaoController@atribuirCheck')->name('atribuicao.check');
    Route::post(  '/atribuir/revisor/lote','AtribuicaoController@atribuirRevisorLote')->name('atribuir.revisor.lote');
    // rota downloadArquivo
    Route::get(   '/downloadArquivo',       'HomeController@downloadArquivo'             )->name('download');
    // rota download do arquivo do trabalho
    Route::get(   '/download-trabalho/{id}',     'TrabalhoController@downloadArquivo'    )->name('downloadTrabalho');
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
    
    // Encontrar resumo especifico para trabalhos
    Route::get(   '/encontrarResumo',    'TrabalhoController@findResumo'                  )->name('trabalhoResumo');
    // Critérios
    Route::post(  '/criterio/', 'CriteriosController@store'                               )->name('cadastrar.criterio');
    Route::post(  '/criterio/{id}/atualizar', 'CriteriosController@update'                )->name('atualizar.criterio');
    Route::get(   '/{evento_id}/criterio/{id}/deletar',   'CriteriosController@destroy'               )->name('criterio.destroy');
    Route::get(   '/encontrarCriterio', 'CriteriosController@findCriterio'                )->name('encontrar.criterio');     
  });
  
  Route::namespace('Users')->group(function () {
  // Controllers Within The "App\Http\Controllers\Admin" Namespace
    Route::prefix('/comissao/cientifica/evento/')->name('comissao.cientifica.')->group(function(){
      Route::get('detalhes', 'ComissaoController@informacoes')->name('detalhesEvento');
    });

    //Revisores
    Route::post(  '/revisor/criar',         'RevisorController@store'                    )->name('revisor.store');
    Route::post('revisores/editarRevisor', 'RevisorController@update')->name('revisor.update');
    Route::get(   '/revisor/listarTrabalhos','RevisorController@indexListarTrabalhos'    )->name('revisor.listarTrabalhos');
    Route::post(  '/revisor/email',         'RevisorController@enviarEmailRevisor'       )->name('revisor.email');
    Route::get(  '{id}/revisor/convite',    'RevisorController@conviteParaEvento'        )->name('revisor.convite.evento');
    Route::post(  '/revisor/emailTodos',    'RevisorController@enviarEmailTodosRevisores')->name('revisor.emailTodos');
    Route::get(  '/revisores-por-area/{id}','RevisorController@revisoresPorAreaAjax'     )->name('revisores.area');
    Route::post(  '/remover/revisor/{id}/{evento_id}',  'RevisorController@destroy'                  )->name('remover.revisor');
    Route::get('/area/revisores/trabalhos/area/{area_id}/modalidade/{modalidade_id}', 'RevisorController@indexListarTrabalhos')->name('avaliar.listar.trabalhos.filtro');
    Route::get('/area/revisores/{id}/trabalhos',  'RevisorController@trabalhosDoEvento' )->name('revisor.trabalhos.evento');
    Route::get('/area/revisores',        'RevisorController@index'                      )->name('revisor.index');
    Route::get('revisores/responde',        'RevisorController@responde'                      )->name('revisor.responde');
    Route::post('revisores/resposta/salvar',        'RevisorController@salvarRespostas'  )->name('revisor.salvar.respostas');
  });
  // Visualizar trabalhos do usuário
  Route::get('/user/trabalhos', 'Users\UserController@meusTrabalhos')->name('user.meusTrabalhos');

  // Cadastrar Comissão
  Route::post('/evento/cadastrarComissao','Users\ComissaoController@store'                   )->name('cadastrar.comissao');
  Route::post('/evento/cadastrarCoordComissao','Users\ComissaoController@coordenadorComissao')->name('cadastrar.coordComissao');

  

  Route::name('coord.')->group(function () {
    Route::get('comissaoOrganizadora/{id}/cadastrar', 'Users\ComissaoOrganizadoraController@create')->name('comissao.organizadora.create');
    Route::get('comissaoOrganizadora/{id}/definir-coordenador', 'Users\ComissaoOrganizadoraController@definirCoordenador')->name('definir.coordComissaoOrganizadora');
    Route::get('comissaoOrganizadora/{id}/listar',    'Users\ComissaoOrganizadoraController@index')->name('listar.comissaoOrganizadora');
    Route::post('remover/comissaoOrganizadora/{id}',  'Users\ComissaoOrganizadoraController@destroy')->name('remover.comissao.organizadora');
    Route::post('remover/comissao/{id}',              'Users\ComissaoController@destroy'      )->name('remover.comissao');
  });
  
  // ROTAS DO MODULO DE INSCRIÇÃO
  Route::get('{id}/inscricoes/nova-inscricao',  'Inscricao\InscricaoController@create')->name('inscricao.create');
  Route::get('inscricoes/atividades-da-promocao','Inscricao\PromocaoController@atividades')->name('promocao.atividades');
  Route::get('inscricoes/checar-cupom',          'Inscricao\CupomDeDescontoController@checar')->name('checar.cupom');
  Route::post('{id}/inscricoes/nova-inscricao/checar', 'Inscricao\InscricaoController@checarDados')->name('inscricao.checar');
  Route::get('{id}/inscricoes/nova-inscricao/voltar', 'Inscricao\InscricaoController@voltarTela')->name('inscricao.voltar');
  Route::post('/inscricoes/salvar-campo-formulario',  'Inscricao\CampoFormularioController@store')->name('campo.formulario.store');
  Route::post('/inscricoes/campo-excluir/{id}',       'Inscricao\CampoFormularioController@destroy')->name('campo.destroy');
  Route::post('inscricoes/editar-campo/{id}',         'Inscricao\CampoFormularioController@update')->name('campo.edit');
  // Checkout 
  Route::prefix('checkout')->name('checkout.')->group(function(){
    Route::post('/confirmar-inscricao/{id}',  'Inscricao\CheckoutController@index')->name('index');
    Route::post('/proccess',  'Inscricao\CheckoutController@proccess')->name('proccess');
    Route::get('/obrigado',  'Inscricao\CheckoutController@obrigado')->name('obrigado');

    Route::post('/notification', 'Inscricao\CheckoutController@notification')->name('notification');
    Route::get('/{id}/pagamentos',    'Inscricao\CheckoutController@listarPagamentos'  )->name('pagamentos');
    Route::post('/pag-boleto',  'Inscricao\CheckoutController@pagBoleto')->name('boleto');

  });
  //Pagamentos

  Route::get('inscricoes/evento-{id}/index',    'Inscricao\InscricaoController@index'   )->name('inscricoes');
  Route::post('inscricoes/criar-promocao',      'Inscricao\PromocaoController@store'    )->name('promocao.store');
  Route::post('inscricoes/{id}/editar-promocao', 'Inscricao\PromocaoController@update')->name('promocao.update');
  Route::post('inscricoes/destroy/{id}-promocao','Inscricao\PromocaoController@destroy' )->name('promocao.destroy');
  Route::get('inscricoes/{idInscricao}/download/{idCampo}',          'Inscricao\InscricaoController@downloadFileCampoExtra')->name('download.arquivo.inscricao');
  Route::post('inscricoes/criar-cupom',         'Inscricao\CupomDeDescontoController@store')->name('cupom.store');
  Route::post('inscricoes/editar-cupom/{id}',        'Inscricao\CupomDeDescontoController@update')->name('cupom.update');
  Route::get('inscricoes/destroy/{id}-cupom',  'Inscricao\CupomDeDescontoController@destroy')->name('cupom.destroy');

  Route::post('inscricoes/criar-categoria-participante', 'Inscricao\CategoriaController@store')->name('categoria.participante.store');
  Route::get('{id}/inscricoes/excluir-categoria',    'Inscricao\CategoriaController@destroy')->name('categoria.destroy');
  Route::post('{id}/inscricoes/atualizar-categoria', 'Inscricao\CategoriaController@update')->name('categoria.participante.update');
  Route::get('valor/categoria',                      'Inscricao\CategoriaController@valorAjax')->name('ajax.valor.categoria');
  Route::get('confirmar-inscricao',            'Inscricao\InscricaoController@store')->name('inscricao.confirmar');

});

// Auth::routes();

Route::get('/demo', function () {
  return new App\Mail\UserWelcome();
});

Route::get('/home', 'HomeController@home')->name('home')->middleware('verified', 'isTemp');

Route::namespace('Submissao')->group(function () {
  Route::get('{id}/modalidade-arquivo-regras',  'ModalidadeController@downloadRegras'  )->name('modalidade.regras.download');
  Route::get('{id}/modalidade-template',      'ModalidadeController@downloadTemplate'  )->name('modalidade.template.download');

});