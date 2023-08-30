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

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Inscricao\CampoFormularioController;
use App\Http\Controllers\Inscricao\CategoriaController;
use App\Http\Controllers\Inscricao\CheckoutController;
use App\Http\Controllers\Inscricao\InscricaoController;
use App\Http\Controllers\Inscricao\PromocaoController;
use App\Http\Controllers\Submissao\AreaController;
use App\Http\Controllers\Submissao\ArquivoInfoController;
use App\Http\Controllers\Submissao\AssinaturaController;
use App\Http\Controllers\Submissao\AtividadeController;
use App\Http\Controllers\Submissao\AtribuicaoController;
use App\Http\Controllers\Submissao\CertificadoController;
use App\Http\Controllers\Submissao\CriteriosController;
use App\Http\Controllers\Submissao\EventoController;
use App\Http\Controllers\Submissao\FormEventoController;
use App\Http\Controllers\Submissao\FormSubmTrabaController;
use App\Http\Controllers\Submissao\MemoriaController;
use App\Http\Controllers\Submissao\MensagemParecerController;
use App\Http\Controllers\Submissao\ModalidadeController;
use App\Http\Controllers\Submissao\PalestranteController;
use App\Http\Controllers\Submissao\TipoAtividadeController;
use App\Http\Controllers\Submissao\TipoComissaoController;
use App\Http\Controllers\Submissao\TrabalhoController;
use App\Http\Controllers\Users\AdministradorController;
use App\Http\Controllers\Users\CoautorController;
use App\Http\Controllers\Users\ComissaoController;
use App\Http\Controllers\Users\ComissaoOrganizadoraController;
use App\Http\Controllers\Users\CoordComissaoCientificaController;
use App\Http\Controllers\Users\CoordComissaoOrganizadoraController;
use App\Http\Controllers\Users\CoordEventoController;
use App\Http\Controllers\Users\MembroComissaoController;
use App\Http\Controllers\Users\RevisorController;
use App\Http\Controllers\Users\UserController;
use App\Models\Submissao\Evento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/index', [HomeController::class, 'index'])->name('index');

Route::view('/termos-de-uso', 'termosdeuso')->name('termos.de.uso');
Route::get('/evento/busca', [EventoController::class, 'buscaLivre'])->name('busca.eventos');
Route::get('/evento/buscar-livre', [EventoController::class, 'buscaLivreAjax'])->name('busca.livre.ajax');

Auth::routes(['verify' => true, 'register' => false]);

Route::group(['prefix' => '{locale}', 'middleware' => 'setLocale'], function () {
    Route::get('/register/{pais?}', function ($locale, $pais = null) {
        return view('auth.register', compact('pais'));
    });
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
});

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('index');
    }

    $eventos = Evento::all();

    return redirect()->route('index');
})->name('cancelarCadastro');

Route::namespace('Submissao')->group(function () {
    Route::get('/evento/{id}', [EventoController::class, 'show'])->name('evento.visualizar');
    Route::get('/evento/visualizar/{id}', function ($id) {
        return redirect()->route('evento.visualizar', $id);
    });
    Route::view('validarCertificado', 'validar')->name('validarCertificado');
    Route::post('validarCertificado', [CertificadoController::class, 'validar'])->name('validarCertificadoPost');
    Route::get('/home', [CertificadoController::class, 'validar'])->name('home')->middleware('verified', 'isTemp');

});

Route::get('/{id}/atividades', [AtividadeController::class, 'atividadesJson'])->name('atividades.json');

Route::get('/perfil/{pais?}', [UserController::class, 'perfil'])->name('perfil')->middleware('auth');
Route::post('/perfil/editar', [UserController::class, 'editarPerfil'])->name('perfil.update')->middleware('auth');

Route::group(['middleware' => ['auth', 'verified', 'isTemp']], function () {
    Route::get('meusCertificados', [CertificadoController::class, 'listarCertificados'])->name('meusCertificados');
    Route::get('/home-user', [HomeController::class, 'index'])->name('home.user');

    Route::namespace('Users')->group(function () {

        Route::get('meusCertificados', [UserController::class, 'meusCertificados'])->name('meusCertificados');

        // rotas do administrador
        Route::prefix('/admin')->name('admin.')->group(function () {
            Route::get('/home', [AdministradorController::class, 'index'])->name('home');
            Route::get('/editais', [AdministradorController::class, 'editais'])->name('editais');
            Route::get('/eventos', [AdministradorController::class, 'eventos'])->name('eventos');
            Route::get('/areas', [AdministradorController::class, 'areas'])->name('areas');
            Route::get('/users', [AdministradorController::class, 'users'])->name('users');
            Route::get('/edit/user/{id}', [AdministradorController::class, 'editUser'])->name('editUser');
            Route::post('/update/user/{id}', [AdministradorController::class, 'updateUser'])->name('updateUser');
            Route::get('/delete/user/{id}', [AdministradorController::class, 'deleteUser'])->name('deleteUser');
            Route::post('/delete/search', [AdministradorController::class, 'search'])->name('search');
        });
        // rotas da Comissao Cientifica
        Route::get('comissao', [MembroComissaoController::class, 'index'])->name('home.membro');
        Route::get('comissaoCientifica/home', [CoordComissaoCientificaController::class, 'index'])->name('cientifica.home');
        Route::get('comissaoCientifica/editais', [CoordComissaoCientificaController::class, 'index'])->name('cientifica.editais');
        Route::get('comissaoCientifica/areas', [CoordComissaoCientificaController::class, 'index'])->name('cientifica.areas');
        Route::post('comissaoCientifica/permissoes', [CoordComissaoCientificaController::class, 'permissoes'])->name('cientifica.permissoes');
        Route::post('comissaoCientifica/novoUsuario', [CoordComissaoCientificaController::class, 'novoUsuario'])->name('cientifica.novoUsuario');
        // rotas do Comissao Organizadora
        Route::get('/home/comissaoOrganizadora', [CoordComissaoOrganizadoraController::class, 'index'])->name('home.organizadora');
        Route::post('comissaoOrganizadora/novoUsuario', [ComissaoOrganizadoraController::class, 'store'])->name('cadastrar.comissaoOrganizadora');
        Route::post('comissaoOrganizadora/salvar-coordenador', [ComissaoOrganizadoraController::class, 'salvarCoordenador'])->name('comissaoOrganizadora.salvaCoordenador');
        // rotas do Coordenador de evento
        Route::get('/home/coord', [CoordEventoController::class, 'index'])->name('coord.index');
        Route::get('/home/coord/eventos', [CoordEventoController::class, 'listaEventos'])->name('coord.eventos');
        //Coautor
        Route::get('coautor/index', [CoautorController::class, 'index'])->name('coautor.index');
    });

    Route::get('search/user', [UserController::class, 'searchUser'])->name('search.user');

    // rotas de teste
    Route::get('/downloadArquivo', [HomeController::class, 'downloadArquivo'])->name('download');
    Route::namespace('Submissao')->group(function () {
        // Controllers Within The "App\Http\Controllers\Admin" Namespace
        Route::get('/coordenador/home', [EventoController::class, 'index'])->name('coord.home');

        //Inscrição em atividade
        Route::post('atividades/{id}/inscrever', [AtividadeController::class, 'inscrever'])->name('atividades.inscricao');
        Route::post('atividades/{id}/{user}/cancelarInscricao', [AtividadeController::class, 'cancelarInscricao'])->name('atividades.cancelarInscricao');
        Route::post('atividades/{id}/{user}/cancelarUmaInscricao', [AtividadeController::class, 'cancelarUmaInscricao'])->name('atividades.cancelarUmaInscricao');
        Route::get('atividades/{id}/exportar', [AtividadeController::class, 'exportInscritos'])->name('atividades.exportar');
        Route::get('atividades/{id}/inscritos', [AtividadeController::class, 'listarInscritos'])->name('atividades.inscritos');

        Route::prefix('/coord/evento/')->name('coord.')->group(function () {
            Route::get('detalhes', [EventoController::class, 'informacoes'])->name('detalhesEvento');
            Route::get('informacoes', [EventoController::class, 'informacoes'])->name('informacoes');
            Route::get('trabalhos/definirSubmissoes', [EventoController::class, 'definirSubmissoes'])->name('definirSubmissoes');
            Route::get('trabalhos/listarTrabalhos/{column?}/{direction?}/{status?}', [EventoController::class, 'listarTrabalhos'])->name('listarTrabalhos');
            Route::get('trabalhos/listarTrabalhosModalidades/{column?}/{direction?}/{status?}', [EventoController::class, 'listarTrabalhosModalidades'])->name('listarTrabalhosModalidades');
            Route::get('trabalhos/{id}/resultados/{column?}/{direction?}/{status?}', [TrabalhoController::class, 'resultados'])->name('resultados');
            Route::post('trabalhos/parecer_final', [TrabalhoController::class, 'parecerFinalTrabalho'])->name('parecer.final');
            Route::get('/trabalhos/parecer_final/info', [TrabalhoController::class, 'infoParecerTrabalho'])->name('parecer.final.info.ajax');

            Route::get('trabalhos/edit/{id}', [TrabalhoController::class, 'edit'])->name('trabalho.edit');

            Route::get('trabalhos/listarAvaliacoes/{column?}/{direction?}/{status?}', [EventoController::class, 'listarAvaliacoes'])->name('listarAvaliacoes');
            Route::get('trabalhos/form/listarRepostasTrabalhos/{column?}/{direction?}/{status?}', [EventoController::class, 'listarRespostasTrabalhos'])->name('respostasTrabalhos');
            Route::get('trabalhos/form/visualizarRespostaFormulario', [EventoController::class, 'visualizarRespostaFormulario'])->name('visualizarRespostaFormulario');
            Route::get('trabalhos/listarCorrecoes/{column?}/{direction?}', [EventoController::class, 'listarCorrecoes'])->name('listarCorrecoes');

            Route::get('areas/cadastrarAreas', [EventoController::class, 'cadastrarAreas'])->name('cadastrarAreas');
            Route::get('areas/listarAreas', [EventoController::class, 'listarAreas'])->name('listarAreas');

            Route::get('revisores/cadastrarRevisores', [EventoController::class, 'cadastrarRevisores'])->name('cadastrarRevisores');

            Route::get('revisores/listarRevisores', [EventoController::class, 'listarRevisores'])->name('listarRevisores');
            Route::get('revisores/listarUsuarios', [EventoController::class, 'listarUsuarios'])->name('listarUsuarios');

            // Regristros de memória
            Route::get('/{evento}/memoria/create', [MemoriaController::class, 'create'])->name('memoria.create');
            Route::get('/{evento}/memoria', [MemoriaController::class, 'index'])->name('memoria.index');
            Route::post('/{evento}/memoria', [MemoriaController::class, 'store'])->name('memoria.store');
            Route::put('/{evento}/memoria/{memoria}', [MemoriaController::class, 'update'])->name('memoria.update');
            Route::delete('/memoria', [MemoriaController::class, 'destroy'])->name('memoria.destroy');

            // Route::get('revisores/{id}/disponiveis', [RevisorController::class, 'listarRevisores'])->name('adicionarRevisores');

            Route::get('comissaoCientifica/cadastrarComissao', [EventoController::class, 'cadastrarComissao'])->name('cadastrarComissao');
            Route::get('comissaoCientifica/definirCoordComissao', [EventoController::class, 'definirCoordComissao'])->name('definirCoordComissao');
            Route::get('comissaoCientifica/listarComissao', [EventoController::class, 'listarComissao'])->name('listarComissao');
            //Outras comissoes
            Route::get('/{evento}/tipocomissao/{comissao}', [TipoComissaoController::class, 'show'])->name('tipocomissao.show');
            Route::get('/{evento}/tipocomissao', [TipoComissaoController::class, 'create'])->name('tipocomissao.create');
            Route::post('/{evento}/tipocomissao', [TipoComissaoController::class, 'store'])->name('tipocomissao.store');
            Route::put('/{evento}/tipocomissao/{comissao}', [TipoComissaoController::class, 'update'])->name('tipocomissao.update');
            Route::delete('/{evento}/tipocomissao/{comissao}', [TipoComissaoController::class, 'destroy'])->name('tipocomissao.destroy');
            Route::post('/{evento}/tipocomissao/{comissao}/addmembro', [TipoComissaoController::class, 'adicionarMembro'])->name('tipocomissao.addmembro');
            Route::put('/{evento}/tipocomissao/{comissao}/editmembro/{membro}', [TipoComissaoController::class, 'editarMembro'])->name('tipocomissao.editmembro');
            Route::delete('/{evento}/tipocomissao/{comissao}/removermembro', [TipoComissaoController::class, 'removerMembro'])->name('tipocomissao.removermembro');
            Route::get('comissao', [TipoComissaoController::class, 'membroIndex'])->name('membroOutrasComissoes');
            //Mensangens de parecer
            Route::get('/{evento}/mensagem/parecer', [MensagemParecerController::class, 'create'])->name('mensagem.parecer.create');
            Route::post('/{evento}/mensagem/parecer', [MensagemParecerController::class, 'store'])->name('mensagem.parecer.store');
            Route::put('/{evento}/mensagem/parecer', [MensagemParecerController::class, 'update'])->name('mensagem.parecer.update');
            //Palestrantes
            Route::get('palestrantes/listarPalestrantes', [PalestranteController::class, 'index'])->name('palestrantes.index');
            Route::get('palestrantes/cadastrarPalestrante', [PalestranteController::class, 'create'])->name('palestrantes.create');
            Route::post('palestrantes/cadastrarPalestrante', [PalestranteController::class, 'store'])->name('palestrantes.store');
            Route::post('palestrantes/exportar/{evento}', [PalestranteController::class, 'exportar'])->name('palestrantes.exportar');
            Route::put('palestrantes/cadastrarPalestrante', [PalestranteController::class, 'update'])->name('palestrantes.update');
            Route::delete('palestrantes/{palestra}/deletePalestra', [PalestranteController::class, 'destroy'])->name('palestrantes.destroy');
            //Assinaturas
            Route::get('certificados/cadastrarAssinatura', [AssinaturaController::class, 'create'])->name('cadastrarAssinatura');
            Route::get('certificados/listarAssinatura', [AssinaturaController::class, 'index'])->name('listarAssinaturas');
            Route::get('certificados/{id}/editarAssinatura', [AssinaturaController::class, 'edit'])->name('editarAssinatura');
            Route::post('certificados/cadastrarAssinatura', [AssinaturaController::class, 'store'])->name('assinatura.store');
            Route::post('certificados/{id}/deleteAssinatura', [AssinaturaController::class, 'destroy'])->name('assinatura.destroy');
            Route::post('certificados/{id}/editAssinatura', [AssinaturaController::class, 'update'])->name('assinatura.update');
            //Certificados
            Route::post('certificados/cadastrarmedida', [CertificadoController::class, 'salvarMedida'])->name('cadastrarmedida');
            Route::get('certificados/cadastrarCertificado', [CertificadoController::class, 'create'])->name('cadastrarCertificado');
            Route::get('certificados/listarEmissoes/{certificado}', [CertificadoController::class, 'listarEmissoes'])->name('listarEmissoes');
            Route::get('certificados/{id}/modelo', [CertificadoController::class, 'modelo'])->name('modeloCertificado');
            Route::get('certificados/{id}/editarCertificado', [CertificadoController::class, 'edit'])->name('editarCertificado');
            Route::get('certificados/emitir', [CertificadoController::class, 'emitir'])->name('emitirCertificado');
            Route::post('certificados/enviar-certificado', [CertificadoController::class, 'enviarCertificacao'])->name('enviarCertificado');
            Route::get('certificados/listarCertificado', [CertificadoController::class, 'index'])->name('listarCertificados');
            Route::post('certificados/cadastrarCertificado', [CertificadoController::class, 'store'])->name('certificado.store');
            Route::post('certificados/{id}/deleteCertificado', [CertificadoController::class, 'destroy'])->name('certificado.destroy');
            Route::post('certificados/{certificado}/duplicar', [CertificadoController::class, 'duplicar'])->name('certificado.duplicar');
            Route::post('certificados/{id}/editCertificado', [CertificadoController::class, 'update'])->name('certificado.update');
            Route::get('certificados/ajax-listar-destinatarios', [CertificadoController::class, 'ajaxDestinatarios'])->name('ajax.listar.destinatarios');
            Route::get('certificados/{certificadoId}/preview-destinatario/{destinatarioId}/trabalho/{trabalhoId}', [CertificadoController::class, 'previewCertificado'])->name('previewCertificado');
            Route::get('certificados/{certificadoId}/ver-destinatario/{destinatarioId}/trabalho/{trabalhoId}', [CertificadoController::class, 'visualizar_certificado_emitido'])->name('verCertificado');
            Route::delete('certificados/emissoes/deletar', [CertificadoController::class, 'deletarEmissao'])->name('deletar.emissao');

            Route::get('modalidade/cadastrarModalidade', [EventoController::class, 'cadastrarModalidade'])->name('cadastrarModalidade');
            Route::get('modalidade/listarModalidade', [EventoController::class, 'listarModalidade'])->name('listarModalidade');
            Route::get('modalidade/cadastrarCriterio', [EventoController::class, 'cadastrarCriterio'])->name('cadastrarCriterio');
            Route::get('modalidade/listarCriterios', [EventoController::class, 'listarCriterios'])->name('listarCriterios');
            Route::get('modalidade/forms', [EventoController::class, 'forms'])->name('forms');
            Route::get('modalidade/atribuir/form', [EventoController::class, 'atribuirForm'])->name('atribuir.form');
            Route::get('modalidade/form/salvar', [EventoController::class, 'salvarForm'])->name('salvar.form');
            Route::get('modalidade/form/update', [EventoController::class, 'updateForm'])->name('update.form');
            Route::get('modalidade/form/visualizar', [EventoController::class, 'visualizarForm'])->name('visualizar.form');
            Route::get('modalidade/form/respostas', [EventoController::class, 'respostas'])->name('respostas');
            Route::get('modalidade/form/respostasToPdf/{modalidade}', [EventoController::class, 'respostasToPdf'])->name('respostasToPdf');
            Route::get('modalidade/form/{id}/excluir', [EventoController::class, 'destroyForm'])->name('deletar.form');

            Route::get('atividades/{id}', [AtividadeController::class, 'index'])->name('atividades');
            // Atenção se mudar url da rota abaixo mudar função setVisibilidadeAtv na view detalhesEvento.blade.php
            Route::post('atividades/{id}/visibilidade', [AtividadeController::class, 'setVisibilidadeAjax'])->name('atividades.visibilidade');
            Route::post('atividade/nova', [AtividadeController::class, 'store'])->name('atividades.store');
            Route::post('atividade/{id}/editar', [AtividadeController::class, 'update'])->name('atividades.update');
            Route::post('atividade/{id}/excluir', [AtividadeController::class, 'destroy'])->name('atividade.destroy');
            Route::post('{id}/atividade/salvar-pdf-programacao', [EventoController::class, 'pdfProgramacao'])->name('evento.pdf.programacao');
            Route::post('{id}/atividade/salvar-pdf-adicional', [EventoController::class, 'pdfAdicional'])->name('evento.pdf.adicional');
            Route::get('tipo-de-atividade/new', [TipoAtividadeController::class, 'storeAjax'])->name('tipo.store.ajax');
            Route::get('eventos/editarEtiqueta', [EventoController::class, 'editarEtiqueta'])->name('editarEtiqueta');
            Route::get('eventos/etiquetasTrabalhos', [EventoController::class, 'etiquetasTrabalhos'])->name('etiquetasTrabalhos');
            Route::get('{id}/modulos', [FormEventoController::class, 'indexModulo'])->name('modulos');
            Route::get('{evento}/arquivos', [ArquivoInfoController::class, 'index'])->name('arquivos-adicionais');
            Route::post('{evento}/arquivos', [ArquivoInfoController::class, 'store'])->name('arquivos-adicionais.store');
            Route::delete('{arquivoInfo}/arquivos', [ArquivoInfoController::class, 'delete'])->name('arquivos-adicionais.delete');
            Route::put('{arquivoInfo}/arquivos', [ArquivoInfoController::class, 'update'])->name('arquivos-adicionais.update');

        });
        //Evento
        Route::get('/criar/evento', [EventoController::class, 'create'])->name('evento.criar');
        Route::post('/evento/criar', [EventoController::class, 'store'])->name('evento.store');
        Route::delete('/evento/excluir/{id}', [EventoController::class, 'destroy'])->name('evento.deletar');
        Route::get('/evento/editar/{id}', [EventoController::class, 'edit'])->name('evento.editar');
        Route::post('/evento/editar/{id}', [EventoController::class, 'update'])->name('evento.update');
        Route::post('/evento/setResumo', [EventoController::class, 'setResumo'])->name('evento.setResumo');
        Route::post('/evento/setFoto', [EventoController::class, 'setFotoEvento'])->name('evento.setFotoEvento');
        Route::post('/evento/numTrabalhos', [EventoController::class, 'numTrabalhos'])->name('trabalho.numTrabalhos');
        Route::get('/evento/habilitar/{id}', [EventoController::class, 'habilitar'])->name('evento.habilitar');
        Route::get('/evento/desabilitar/{id}', [EventoController::class, 'desabilitar'])->name('evento.desabilitar');

        //Sub-Evento
        Route::get('/subevento/criar/{id}', [EventoController::class, 'createSubEvento'])->name('subevento.criar');
        //Modalidade
        Route::post('/modalidade/criar', [ModalidadeController::class, 'store'])->name('modalidade.store');
        Route::post('/modalidade/{id}/delete', [ModalidadeController::class, 'destroy'])->name('modalidade.destroy');
        //Area
        Route::post('/area/criar', [AreaController::class, 'store'])->name('area.store');
        Route::delete('/area/deletar/{id}', [AreaController::class, 'destroy'])->name('area.destroy');
        Route::post('/area/editar/{id}', [AreaController::class, 'update'])->name('area.update');

        //AreaModalidade
        // Route::post(  '/areaModalidade/criar',  [AreaModalidadeController::class, 'store']             )->name('areaModalidade.store');

        //Trabalho
        Route::get('/trabalho/submeter/{id}/{idModalidade}', [TrabalhoController::class, 'index'])->name('trabalho.index');
        Route::post('/trabalho/novaVersao', [TrabalhoController::class, 'novaVersao'])->name('trabalho.novaVersao');
        Route::post('/trabalho/criar/{id}', [TrabalhoController::class, 'store'])->name('trabalho.store');
        Route::get('/trabalho/pesquisa', [TrabalhoController::class, 'pesquisaAjax'])->name('trabalho.pesquisa.ajax');
        Route::post('/trabalho/{id}/avaliar', [TrabalhoController::class, 'avaliarTrabalho'])->name('trabalho.avaliacao.revisor');
        Route::post('/trabalho/{id}/excluir', [TrabalhoController::class, 'destroy'])->name('excluir.trabalho');
        Route::post('/trabalho/{id}/editar', [TrabalhoController::class, 'update'])->name('editar.trabalho');
        Route::get('/trabalho/status/{id}/{status}', [TrabalhoController::class, 'statusTrabalho'])->name('trabalho.status');
        Route::get('/trabalho/encaminhar/{id}/{revisor}', [TrabalhoController::class, 'encaminharTrabalho'])->name('trabalho.encaminhar');
        Route::post('/trabalho/{id}/aprovar-reprovar', [TrabalhoController::class, 'aprovacaoTrabalho'])->name('trabalho.aprovar-reprovar');
        Route::post('/trabalho/{id}/correcao', [TrabalhoController::class, 'correcaoTrabalho'])->name('trabalho.correcao');
        //Atribuição
        Route::get('/atribuir', [AtribuicaoController::class, 'distribuicaoAutomatica'])->name('distribuicao');
        Route::get('/atribuirPorArea', [AtribuicaoController::class, 'distribuicaoPorArea'])->name('distribuicaoAutomaticaPorArea');
        Route::post('/distribuicaoManual', [AtribuicaoController::class, 'distribuicaoManual'])->name('distribuicaoManual');
        Route::post('{id}/removerAtribuicao', [AtribuicaoController::class, 'deletePorRevisores'])->name('atribuicao.delete');
        Route::post('/atribuir/check', [AtribuicaoController::class, 'atribuirCheck'])->name('atribuicao.check');
        Route::post('/atribuir/revisor/lote', [AtribuicaoController::class, 'atribuirRevisorLote'])->name('atribuir.revisor.lote');
        // rota downloadArquivo

        // rota download do arquivo do trabalho
        Route::get('/download-trabalho/{id}', [TrabalhoController::class, 'downloadArquivo'])->name('downloadTrabalho');
        Route::get('/download-trabalho/{id}/midia-extra/{id_midia}', [TrabalhoController::class, 'downloadMidiaExtra'])->name('downloadMidiaExtra');
        //rota download do arquivo do trabalho
        Route::get('/download-avaliacao', [TrabalhoController::class, 'downloadArquivoAvaliacao'])->name('downloadAvaliacao');
        Route::get('/trabalho/{id}/download-correcao', [TrabalhoController::class, 'downloadArquivoCorrecao'])->name('downloadCorrecao');
        // rota download da foto do evento
        Route::get('/download-logo-evento/{id}', [EventoController::class, 'downloadFotoEvento'])->name('download.foto.evento');

        // atualizar etiquetas do form de eventos
        Route::post('/etiquetas/editar/{id}', [FormEventoController::class, 'update'])->name('etiquetas.update');
        // atualizar etiquetas do form de submissão de trabalhos
        Route::post('/etiquetas/submissao_trabalhos/editar/{id}', [FormSubmTrabaController::class, 'update'])->name('etiquetas_sub_trabalho.update');
        // Inserir novos campos para o form de submissão de trabalhos
        Route::post('/adicionarnovocampo/{id}', [FormSubmTrabaController::class, 'store'])->name('novocampo.store');
        // Exibir ou ocultar modulos do card de eventos
        Route::post('/modulos/{id}', [FormEventoController::class, 'exibirModulo'])->name('exibir.modulo');
        // Ajax para encontrar modalidade especifica e enviar para o modal de edição
        Route::get('/encontrarModalidade', [ModalidadeController::class, 'find'])->name('findModalidade');
        // Ajax para encontrar modalidade especifica e enviar para o modal de edição
        Route::post('/atualizarModalidade', [ModalidadeController::class, 'update'])->name('modalidade.update');
        //

        Route::get('/evento/{evento}/downloadResumos', [EventoController::class, 'resumosToPdf'])->name('evento.downloadResumos');

        Route::get('/evento/{evento}/downloadInscritos', [EventoController::class, 'exportInscritos'])->name('evento.downloadInscritos');
        Route::get('/evento/{evento}/downloadTrabalhos', [EventoController::class, 'exportTrabalhos'])->name('evento.downloadTrabalhos');
        Route::get('/evento/{evento}/downloadAvaliacoes/{modalidade}/form/{form}', [EventoController::class, 'exportAvaliacoes'])->name('evento.downloadAvaliacoes');

        // Encontrar resumo especifico para trabalhos
        Route::get('/encontrarResumo', [TrabalhoController::class, 'findResumo'])->name('trabalhoResumo');
        // Critérios
        Route::post('/criterio/', [CriteriosController::class, 'store'])->name('cadastrar.criterio');
        Route::post('/criterio/{id}/atualizar', [CriteriosController::class, 'update'])->name('atualizar.criterio');
        Route::get('/{evento_id}/criterio/{id}/deletar', [CriteriosController::class, 'destroy'])->name('criterio.destroy');
        Route::get('/encontrarCriterio', [CriteriosController::class, 'findCriterio'])->name('encontrar.criterio');
    });

    Route::namespace('Users')->group(function () {
        // Controllers Within The "App\Http\Controllers\Admin" Namespace
        Route::prefix('/comissao/cientifica/evento/')->name('comissao.cientifica.')->group(function () {
            Route::get('detalhes', [ComissaoController::class, 'informacoes'])->name('detalhesEvento');
        });

        //Revisores
        Route::post('/revisor/criar', [RevisorController::class, 'store'])->name('revisor.store');
        Route::post('revisores/editarRevisor', [RevisorController::class, 'update'])->name('revisor.update');
        Route::get('/revisor/listarTrabalhos', [RevisorController::class, 'indexListarTrabalhos'])->name('revisor.listarTrabalhos');
        Route::post('/revisor/email', [RevisorController::class, 'enviarEmailRevisor'])->name('revisor.email');
        Route::get('{id}/revisor/convite', [RevisorController::class, 'conviteParaEvento'])->name('revisor.convite.evento');
        Route::post('/revisor/emailTodos', [RevisorController::class, 'enviarEmailTodosRevisores'])->name('revisor.emailTodos');
        Route::post('{evento}/revisor/emailCadastroTodos', [RevisorController::class, 'enviarEmailCadastroTodosRevisores'])->name('revisor.emailCadastroTodos');
        Route::get('/revisores-por-area/{id}', [RevisorController::class, 'revisoresPorAreaAjax'])->name('revisores.area');
        Route::post('/remover/revisor/{id}/{evento_id}', [RevisorController::class, 'destroy'])->name('remover.revisor');
        Route::get('/area/revisores/trabalhos/area/{area_id}/modalidade/{modalidade_id}', [RevisorController::class, 'indexListarTrabalhos'])->name('avaliar.listar.trabalhos.filtro');
        Route::get('/area/revisores/{id}/trabalhos', [RevisorController::class, 'trabalhosDoEvento'])->name('revisor.trabalhos.evento');
        Route::get('/area/revisores', [RevisorController::class, 'index'])->name('revisor.index');
        Route::get('revisores/responde', [RevisorController::class, 'responde'])->name('revisor.responde');
        Route::post('revisores/resposta/salvar', [RevisorController::class, 'salvarRespostas'])->name('revisor.salvar.respostas');
        Route::post('revisores/resposta/editar', [RevisorController::class, 'editarRespostasFormulario'])->name('revisor.editar.respostas');
        Route::post('/reenviaremail/revisor/{id}/{evento_id}', [RevisorController::class, 'reenviarEmailRevisor'])->name('revisor.reenviarEmail');
    });
    // Visualizar trabalhos do usuário
    Route::get('/user/trabalhos', [UserController::class, 'meusTrabalhos'])->name('user.meusTrabalhos');
    Route::get('/user/trabalho/{id}/parecer/', [UserController::class, 'visualizarParecer'])->name('user.visualizarParecer');

    // Cadastrar Comissão
    Route::post('/evento/cadastrarComissao', [ComissaoController::class, 'store'])->name('cadastrar.comissao');
    Route::post('/evento/cadastrarCoordComissao', [ComissaoController::class, 'coordenadorComissao'])->name('cadastrar.coordComissao');

    Route::name('coord.')->group(function () {
        Route::get('comissaoOrganizadora/{id}/cadastrar', [ComissaoOrganizadoraController::class, 'create'])->name('comissao.organizadora.create');
        Route::get('comissaoOrganizadora/{id}/definir-coordenador', [ComissaoOrganizadoraController::class, 'definirCoordenador'])->name('definir.coordComissaoOrganizadora');
        Route::get('comissaoOrganizadora/{id}/listar', [ComissaoOrganizadoraController::class, 'index'])->name('listar.comissaoOrganizadora');
        Route::post('remover/comissaoOrganizadora/{id}', [ComissaoOrganizadoraController::class, 'destroy'])->name('remover.comissao.organizadora');
        Route::post('remover/comissao/{id}', [ComissaoController::class, 'destroy'])->name('remover.comissao');
    });

    // ROTAS DO MODULO DE INSCRIÇÃO
    Route::get('{evento}/inscricoes', [InscricaoController::class, 'inscritos'])->name('inscricao.inscritos');
    Route::get('{evento}/inscricoes/formulario', [InscricaoController::class, 'formulario'])->name('inscricao.formulario');
    Route::get('{evento}/inscricoes/categorias', [InscricaoController::class, 'categorias'])->name('inscricao.categorias');
    Route::get('{id}/inscricoes/nova-inscricao', [InscricaoController::class, 'create'])->name('inscricao.create');
    Route::post('/inscricoes/inscrever', [InscricaoController::class, 'inscrever'])->name('inscricao.inscrever');
    Route::post('inscricoes/{inscricao}/aprovar', [InscricaoController::class, 'aprovar'])->name('coord.inscricoes.aprovar');
    Route::get('inscricoes/atividades-da-promocao', [PromocaoController::class, 'atividades'])->name('promocao.atividades');
    Route::get('inscricoes/checar-cupom', [CupomDeDescontoController::class, 'checar'])->name('checar.cupom');
    Route::post('{id}/inscricoes/nova-inscricao/checar', [InscricaoController::class, 'checarDados'])->name('inscricao.checar');
    Route::get('{id}/inscricoes/nova-inscricao/voltar', [InscricaoController::class, 'voltarTela'])->name('inscricao.voltar');
    Route::post('/inscricoes/salvar-campo-formulario', [CampoFormularioController::class, 'store'])->name('campo.formulario.store');
    Route::post('/inscricoes/campo-excluir/{id}', [CampoFormularioController::class, 'destroy'])->name('campo.destroy');
    Route::post('inscricoes/editar-campo/{id}', [CampoFormularioController::class, 'update'])->name('campo.edit');
    // Checkout
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::post('/confirmar-inscricao/{id}', [CheckoutController::class, 'index'])->name('index');
        Route::post('/proccess', [CheckoutController::class, 'proccess'])->name('proccess');
        Route::get('/obrigado', [CheckoutController::class, 'obrigado'])->name('obrigado');

        Route::post('/notification', [CheckoutController::class, 'notification'])->name('notification');
        Route::get('/{id}/pagamentos', [CheckoutController::class, 'listarPagamentos'])->name('pagamentos');
        Route::post('/pag-boleto', [CheckoutController::class, 'pagBoleto'])->name('boleto');

    });
    //Pagamentos

    Route::get('inscricoes/evento-{id}/index', [InscricaoController::class, 'index'])->name('inscricoes');
    Route::post('inscricoes/criar-promocao', [PromocaoController::class, 'store'])->name('promocao.store');
    Route::post('inscricoes/{id}/editar-promocao', [PromocaoController::class, 'update'])->name('promocao.update');
    Route::post('inscricoes/destroy/{id}-promocao', [PromocaoController::class, 'destroy'])->name('promocao.destroy');
    Route::get('inscricoes/{idInscricao}/download/{idCampo}', [InscricaoController::class, 'downloadFileCampoExtra'])->name('download.arquivo.inscricao');
    Route::post('inscricoes/criar-cupom', [CupomDeDescontoController::class, 'store'])->name('cupom.store');
    Route::post('inscricoes/editar-cupom/{id}', [CupomDeDescontoController::class, 'update'])->name('cupom.update');
    Route::get('inscricoes/destroy/{id}-cupom', [CupomDeDescontoController::class, 'destroy'])->name('cupom.destroy');

    Route::post('inscricoes/criar-categoria-participante', [CategoriaController::class, 'store'])->name('categoria.participante.store');
    Route::delete('{id}/inscricoes/excluir-categoria', [CategoriaController::class, 'destroy'])->name('categoria.participante.destroy');
    Route::put('{id}/inscricoes/atualizar-categoria', [CategoriaController::class, 'update'])->name('categoria.participante.update');
    Route::get('valor/categoria', [CategoriaController::class, 'valorAjax'])->name('ajax.valor.categoria');
    Route::get('confirmar-inscricao', [InscricaoController::class, 'store'])->name('inscricao.confirmar');

});

// Auth::routes();

Route::get('/demo', function () {
    return new App\Mail\UserWelcome();
});

Route::get('/home', [HomeController::class, 'home'])->name('home')->middleware('verified', 'isTemp');

Route::namespace('Submissao')->group(function () {
    Route::get('{id}/regras', [ModalidadeController::class, 'downloadRegras'])->name('modalidade.regras.download');
    Route::get('{modalidade}/instrucoes', [ModalidadeController::class, 'downloadInstrucoes'])->name('modalidade.instrucoes.download');
    Route::get('{id}/modalidade-arquivo-modelos', [ModalidadeController::class, 'downloadModelos'])->name('modalidade.modelos.download');
    Route::get('{id}/modalidade-template', [ModalidadeController::class, 'downloadTemplate'])->name('modalidade.template.download');

});
