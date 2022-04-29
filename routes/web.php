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

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Submissao\Assinatura;
use App\Models\Submissao\Certificado;
use Illuminate\Support\Facades\Log;
use App\Models\Submissao\Evento;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

Route::get('/index', 'HomeController@index')->name('index');
Route::get('/evento/busca', 'Submissao\EventoController@buscaLivre')->name('busca.eventos');
Route::get('/evento/buscar-livre', 'Submissao\EventoController@buscaLivreAjax')->name('busca.livre.ajax');




Auth::routes(['verify' => true]);

Route::get('/', function () {
    if(Auth::check()){
      return redirect()->route('index');
    }

    $eventos = Evento::all();
    return redirect()->route('index');
})->name('cancelarCadastro');

Route::namespace('Submissao')->group(function () {
  Route::get('/evento/visualizar/naologado/{id}','EventoController@showNaoLogado')->name('evento.visualizarNaoLogado');
  Route::view('validarCertificado', 'validar')->name('validarCertificado');
  Route::post('validarCertificado', 'CertificadoController@validar')->name('validarCertificadoPost');
  Route::get('/home', 'Submissao\CertificadoController@validar')->name('home')->middleware('verified', 'isTemp');

});


Route::get('/{id}/atividades', 'Submissao\AtividadeController@atividadesJson')->name('atividades.json');

Route::get('/perfil','Users\UserController@perfil')->name('perfil')->middleware('auth');
Route::post('/perfil/editar','Users\UserController@editarPerfil')->name('perfil.update')->middleware('auth');


Route::group(['middleware' => [ 'auth','verified', 'isTemp']], function(){
    Route::get('meusCertificados', 'CertificadoController@listarCertificados')->name('meusCertificados');
  Route::get('/home-user', 'HomeController@index')->name('home.user');

  Route::namespace('Users')->group(function () {

    Route::get('meusCertificados', 'UserController@meusCertificados')->name('meusCertificados');

    // rotas do administrador
    Route::prefix('/admin')->name('admin.')->group(function(){
        Route::get('/home', 'AdministradorController@index')->name('home');
        Route::get('/editais', 'AdministradorController@editais')->name('editais');
        Route::get('/areas', 'AdministradorController@areas')->name('areas');
        Route::get('/users', 'AdministradorController@users')->name('users');
        Route::get('/edit/user/{id}', 'AdministradorController@editUser')->name('editUser');
        Route::post('/update/user/{id}', 'AdministradorController@updateUser')->name('updateUser');
        Route::get('/delete/user/{id}', 'AdministradorController@deleteUser')->name('deleteUser');
        Route::post('/delete/search', 'AdministradorController@search')->name('search');
    });
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

  Route::get('search/user', 'Users\UserController@searchUser')->name('search.user');

  // rotas de teste
  Route::get(   '/downloadArquivo',       'HomeController@downloadArquivo'             )->name('download');
  Route::namespace('Submissao')->group(function () {
    // Controllers Within The "App\Http\Controllers\Admin" Namespace
    Route::get('/coordenador/home','EventoController@index')->name('coord.home');
    Route::prefix('/coord/evento/')->name('coord.')->group(function(){
      Route::get('detalhes', 'EventoController@informacoes')->name('detalhesEvento');
      Route::get('informacoes', 'EventoController@informacoes')->name('informacoes');
      Route::get('trabalhos/definirSubmissoes', 'EventoController@definirSubmissoes')->name('definirSubmissoes');
      Route::get('trabalhos/listarTrabalhos/{column?}/{direction?}/{status?}', 'EventoController@listarTrabalhos')->name('listarTrabalhos');
      Route::get('trabalhos/listarTrabalhosModalidades/{column?}/{direction?}/{status?}', 'EventoController@listarTrabalhosModalidades')->name('listarTrabalhosModalidades');
      Route::get('trabalhos/{id}/resultados', 'TrabalhoController@resultados')->name('resultados');
      Route::get('trabalhos/edit/{id}', 'TrabalhoController@edit')->name('trabalho.edit');

      Route::get('trabalhos/listarAvaliacoes/{column?}/{direction?}/{status?}', 'EventoController@listarAvaliacoes')->name('listarAvaliacoes');
      Route::get('trabalhos/form/listarRepostasTrabalhos/{column?}/{direction?}/{status?}', 'EventoController@listarRespostasTrabalhos')->name('respostasTrabalhos');
      Route::get('trabalhos/form/visualizarRespostaFormulario', 'EventoController@visualizarRespostaFormulario')->name('visualizarRespostaFormulario');
      Route::get('trabalhos/listarCorrecoes/{column?}/{direction?}', 'EventoController@listarCorrecoes')->name('listarCorrecoes');

      Route::get('areas/cadastrarAreas', 'EventoController@cadastrarAreas')->name('cadastrarAreas');
      Route::get('areas/listarAreas', 'EventoController@listarAreas')->name('listarAreas');

      Route::get('revisores/cadastrarRevisores', 'EventoController@cadastrarRevisores')->name('cadastrarRevisores');

      Route::get('revisores/listarRevisores', 'EventoController@listarRevisores')->name('listarRevisores');
      Route::get('revisores/listarUsuarios', 'EventoController@listarUsuarios')->name('listarUsuarios');



      // Route::get('revisores/{id}/disponiveis', 'RevisorController@listarRevisores')->name('adicionarRevisores');

      Route::get('comissaoCientifica/cadastrarComissao', 'EventoController@cadastrarComissao')->name('cadastrarComissao');
      Route::get('comissaoCientifica/definirCoordComissao', 'EventoController@definirCoordComissao')->name('definirCoordComissao');
      Route::get('comissaoCientifica/listarComissao', 'EventoController@listarComissao')->name('listarComissao');
      //Outras comissoes
      Route::get(   '/{evento}/tipocomissao/{comissao}', 'TipoComissaoController@show')->name(   'tipocomissao.show');
      Route::get(   '/{evento}/tipocomissao',            'TipoComissaoController@create')->name( 'tipocomissao.create');
      Route::post(  '/{evento}/tipocomissao',            'TipoComissaoController@store')->name(  'tipocomissao.store');
      Route::put(   '/{evento}/tipocomissao/{comissao}', 'TipoComissaoController@update')->name( 'tipocomissao.update');
      Route::delete('/{evento}/tipocomissao/{comissao}', 'TipoComissaoController@destroy')->name('tipocomissao.destroy');
      Route::post(  '/{evento}/tipocomissao/{comissao}/addmembro', 'TipoComissaoController@adicionarMembro')->name('tipocomissao.addmembro');
      Route::delete(  '/{evento}/tipocomissao/{comissao}/removermembro', 'TipoComissaoController@removerMembro')->name('tipocomissao.removermembro');
      //Palestrantes
      Route::get('palestrantes/listarPalestrantes', 'PalestranteController@index')->name('palestrantes.index');
      Route::get('palestrantes/cadastrarPalestrante', 'PalestranteController@create')->name('palestrantes.create');
      Route::post('palestrantes/cadastrarPalestrante',  'PalestranteController@store')->name('palestrantes.store');
      Route::put('palestrantes/cadastrarPalestrante',  'PalestranteController@update')->name('palestrantes.update');
      Route::delete('palestrantes/{palestra}/deletePalestra',  'PalestranteController@destroy')->name('palestrantes.destroy');
      //Assinaturas
      Route::get('certificados/cadastrarAssinatura', 'AssinaturaController@create')->name('cadastrarAssinatura');
      Route::get('certificados/listarAssinatura', 'AssinaturaController@index')->name('listarAssinaturas');
      Route::get('certificados/{id}/editarAssinatura', 'AssinaturaController@edit')->name('editarAssinatura');
      Route::post('certificados/cadastrarAssinatura',      'AssinaturaController@store')->name('assinatura.store');
      Route::post('certificados/{id}/deleteAssinatura',  'AssinaturaController@destroy')->name('assinatura.destroy');
      Route::post('certificados/{id}/editAssinatura',  'AssinaturaController@update')->name('assinatura.update');
      //Certificados
      Route::post('certificados/cadastrarmedida', 'CertificadoController@salvarMedida')->name('cadastrarmedida');
      Route::get('certificados/cadastrarCertificado', 'CertificadoController@create')->name('cadastrarCertificado');
      Route::get('certificados/listarEmissoes/{certificado}', 'CertificadoController@listarEmissoes')->name('listarEmissoes');
      Route::get('certificados/{id}/modelo', 'CertificadoController@modelo')->name('modeloCertificado');
      Route::get('certificados/{id}/editarCertificado', 'CertificadoController@edit')->name('editarCertificado');
      Route::get('certificados/emitir', 'CertificadoController@emitir')->name('emitirCertificado');
      Route::post('certificados/enviar-certificado', 'CertificadoController@enviarCertificacao')->name('enviarCertificado');
      Route::get('certificados/listarCertificado', 'CertificadoController@index')->name('listarCertificados');
      Route::post('certificados/cadastrarCertificado',      'CertificadoController@store')->name('certificado.store');
      Route::post('certificados/{id}/deleteCertificado',  'CertificadoController@destroy')->name('certificado.destroy');
      Route::post('certificados/{id}/editCertificado',  'CertificadoController@update')->name('certificado.update');
      Route::get('certificados/ajax-listar-destinatarios', 'CertificadoController@ajaxDestinatarios')->name('ajax.listar.destinatarios');
      Route::get('certificados/{certificadoId}/preview-destinatario/{destinatarioId}/trabalho/{trabalhoId}', 'CertificadoController@previewCertificado')->name('previewCertificado');
      Route::get('certificados/{certificadoId}/ver-destinatario/{destinatarioId}/trabalho/{trabalhoId}', 'CertificadoController@visualizar_certificado_emitido')->name('verCertificado');

      Route::get('modalidade/cadastrarModalidade', 'EventoController@cadastrarModalidade')->name('cadastrarModalidade');
      Route::get('modalidade/listarModalidade', 'EventoController@listarModalidade')->name('listarModalidade');
      Route::get('modalidade/cadastrarCriterio', 'EventoController@cadastrarCriterio')->name('cadastrarCriterio');
      Route::get('modalidade/listarCriterios', 'EventoController@listarCriterios')->name('listarCriterios');
      Route::get('modalidade/forms', 'EventoController@forms')->name('forms');
      Route::get('modalidade/atribuir/form', 'EventoController@atribuirForm')->name('atribuir.form');
      Route::get('modalidade/form/salvar', 'EventoController@salvarForm')->name('salvar.form');
      Route::get('modalidade/form/update', 'EventoController@updateForm')->name('update.form');
      Route::get('modalidade/form/visualizar', 'EventoController@visualizarForm')->name('visualizar.form');
      Route::get('modalidade/form/respostas', 'EventoController@respostas')->name('respostas');
      Route::get('modalidade/form/respostasToPdf/{modalidade}', 'EventoController@respostasToPdf')->name('respostasToPdf');
      Route::get('modalidade/form/{id}/excluir', 'EventoController@destroyForm')->name('deletar.form');

      Route::get('atividades/{id}', 'AtividadeController@index')->name('atividades');
      // Atenção se mudar url da rota abaixo mudar função setVisibilidadeAtv na view detalhesEvento.blade.php
      Route::post('atividades/{id}/visibilidade', 'AtividadeController@setVisibilidadeAjax')->name('atividades.visibilidade');
      Route::post('atividade/nova', 'AtividadeController@store')->name('atividades.store');
      Route::post('atividade/{id}/editar', 'AtividadeController@update')->name('atividades.update');
      Route::post('atividade/{id}/excluir', 'AtividadeController@destroy')->name('atividade.destroy');
      Route::post('{id}/atividade/salvar-pdf-programacao', 'EventoController@pdfProgramacao')->name('evento.pdf.programacao');
      Route::post('{id}/atividade/salvar-pdf-adicional', 'EventoController@pdfAdicional')->name('evento.pdf.adicional');
      Route::get('tipo-de-atividade/new', 'TipoAtividadeController@storeAjax')->name('tipo.store.ajax');
      Route::get('eventos/editarEtiqueta', 'EventoController@editarEtiqueta')->name('editarEtiqueta');
      Route::get('eventos/etiquetasTrabalhos', 'EventoController@etiquetasTrabalhos')->name('etiquetasTrabalhos');
      Route::get('{id}/modulos',              'FormEventoController@indexModulo'            )->name('modulos');
      Route::get('{evento}/arquivos', 'ArquivoInfoController@index')->name('arquivos-adicionais');
      Route::post('{evento}/arquivos', 'ArquivoInfoController@store')->name('arquivos-adicionais.store');
      Route::delete('{arquivoInfo}/arquivos', 'ArquivoInfoController@delete')->name('arquivos-adicionais.delete');
      Route::put('{arquivoInfo}/arquivos', 'ArquivoInfoController@update')->name('arquivos-adicionais.update');



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

    //Sub-Evento
    Route::get('/subevento/criar/{id}',          'EventoController@createSubEvento'                    )->name('subevento.criar');
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
    Route::post(  '/trabalho/criar/{id}',        'TrabalhoController@store'             )->name('trabalho.store');
    Route::get(  '/trabalho/pesquisa','TrabalhoController@pesquisaAjax'                  )->name('trabalho.pesquisa.ajax');
    Route::post(  '/trabalho/{id}/avaliar', 'TrabalhoController@avaliarTrabalho'         )->name('trabalho.avaliacao.revisor');
    Route::post( '/trabalho/{id}/excluir',   'TrabalhoController@destroy'                )->name('excluir.trabalho');
    Route::post(  '/trabalho/{id}/editar',   'TrabalhoController@update'                 )->name('editar.trabalho');
    Route::get(  '/trabalho/status/{id}/{status}',  'TrabalhoController@statusTrabalho'  )->name('trabalho.status');
    Route::post(  '/trabalho/{id}/aprovar-reprovar',  'TrabalhoController@aprovacaoTrabalho'  )->name('trabalho.aprovar-reprovar');
    Route::post(  '/trabalho/{id}/correcao',  'TrabalhoController@correcaoTrabalho'  )->name('trabalho.correcao');
    //Atribuição
    Route::get(   '/atribuir',              'AtribuicaoController@distribuicaoAutomatica')->name('distribuicao');
    Route::get(   '/atribuirPorArea',       'AtribuicaoController@distribuicaoPorArea'   )->name('distribuicaoAutomaticaPorArea');
    Route::post(  '/distribuicaoManual',    'AtribuicaoController@distribuicaoManual'    )->name('distribuicaoManual');
    Route::post(  '{id}/removerAtribuicao',     'AtribuicaoController@deletePorRevisores')->name('atribuicao.delete');
    Route::post(  '/atribuir/check',                 'AtribuicaoController@atribuirCheck')->name('atribuicao.check');
    Route::post(  '/atribuir/revisor/lote','AtribuicaoController@atribuirRevisorLote')->name('atribuir.revisor.lote');
    // rota downloadArquivo

    // rota download do arquivo do trabalho
    Route::get(   '/download-trabalho/{id}',     'TrabalhoController@downloadArquivo'    )->name('downloadTrabalho');
    //rota download do arquivo do trabalho
    Route::get(   '/download-avaliacao',     'TrabalhoController@downloadArquivoAvaliacao'    )->name('downloadAvaliacao');
    Route::get(   '/trabalho/{id}/download-correcao',     'TrabalhoController@downloadArquivoCorrecao'    )->name('downloadCorrecao');
    // rota download da foto do evento
    Route::get(   '/download-logo-evento/{id}',   'EventoController@downloadFotoEvento'  )->name('download.foto.evento');

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

    Route::get('/evento/{evento}/downloadResumos', 'EventoController@resumosToPdf')->name('evento.downloadResumos');

    Route::get('/evento/{evento}/downloadInscritos', 'EventoController@exportInscritos')->name('evento.downloadInscritos');

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
    Route::post('revisores/resposta/editar',        'RevisorController@editarRespostasFormulario'  )->name('revisor.editar.respostas');
    Route::post('/reenviaremail/revisor/{id}/{evento_id}', 'RevisorController@reenviarEmailRevisor')->name('revisor.reenviarEmail');
  });
  // Visualizar trabalhos do usuário
  Route::get('/user/trabalhos', 'Users\UserController@meusTrabalhos')->name('user.meusTrabalhos');
  Route::get('/user/trabalho/{id}/parecer/', 'Users\UserController@visualizarParecer')->name('user.visualizarParecer');

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
  Route::post('/inscricoes/inscrever',  'Inscricao\InscricaoController@inscrever')->name('inscricao.inscrever');
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
  Route::get('{id}/regras',  'ModalidadeController@downloadRegras'  )->name('modalidade.regras.download');
  Route::get('{id}/modalidade-arquivo-modelos',  'ModalidadeController@downloadModelos'  )->name('modalidade.modelos.download');
  Route::get('{id}/modalidade-template',      'ModalidadeController@downloadTemplate'  )->name('modalidade.template.download');

});
