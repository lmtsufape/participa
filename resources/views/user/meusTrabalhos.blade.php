@extends('layouts.app')

@section('content')

    {{-- BLOCO 1: MODAIS DE EDIÇÃO E SUBMISSÃO DE NOVA VERSÃO (Manter no início) --}}
    @foreach ($trabalhos as $trabalho)
        <div class="modal fade" id="modalTrabalho_{{ $trabalho->id }}" tabindex="-1" role="dialog"
            aria-labelledby="modalTrabalho" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #114048ff; color: white;">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Submeter nova versão</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                            style="color: white;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('trabalho.novaVersao') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">

                            <div class="row justify-content-center">
                                <div class="col-sm-12">
                                    @error('error')
                                        <div class="alert alert-danger">
                                            <p>{{ $message }}</p>
                                        </div>
                                    @enderror
                                    @error('tipoExtensao')
                                        <div class="alert alert-danger">
                                            <p>{{ $message }}</p>
                                        </div>
                                    @enderror
                                    <input type="hidden" name="trabalhoId" value="{{ $trabalho->id }}"
                                        id="trabalhoNovaVersaoId">

                                    {{-- Arquivo  --}}
                                    <label for="nomeTrabalho"
                                        class="col-form-label">{{ __('Novo arquivo para ') }}{{ $trabalho->titulo }}</label>

                                    <div class="custom-file">
                                        <input type="file" class="filestyle" data-placeholder="Nenhum arquivo"
                                            data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivo">
                                    </div>

                                    <small>Arquivos aceitos nos formatos
                                        @if ($trabalho->modalidade->pdf == true)
                                            <span> - pdf</span>
                                        @endif
                                        @if ($trabalho->modalidade->jpg == true)
                                            <span> - jpg</span>
                                        @endif
                                        @if ($trabalho->modalidade->jpeg == true)
                                            <span> - jpeg</span>
                                        @endif
                                        @if ($trabalho->modalidade->png == true)
                                            <span> - png</span>
                                        @endif
                                        @if ($trabalho->modalidade->docx == true)
                                            <span> - docx</span>
                                        @endif
                                        @if ($trabalho->modalidade->odt == true)
                                            <span> - odt</span>
                                        @endif
                                        @if ($trabalho->modalidade->zip == true)
                                            <span> - zip</span>
                                        @endif
                                        @if ($trabalho->modalidade->svg == true)
                                            <span> - svg</span>
                                        @endif.
                                    </small>
                                    @error('arquivo')
                                        <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <div class="container content">
        {{-- titulo da página --}}
        <div class="row justify-content-center titulo-detalhes">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-12">
                        <h1>Meus Trabalhos</h1>
                    </div>

                </div>
            </div>
        </div>
        <br>
        @if (session('mensagem'))
            <div class="col-md-12" style="margin-top: 5px;">
                <div class="alert alert-success">
                    <p>{{ session('mensagem') }}</p>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-sm-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
        <div class="row margin">
            <div class="col-sm-12 info-evento">
                <h4>Como Autor</h4>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-12">

                @if (count($trabalhos) > 0)
                    <table class="table table-responsive-lg table-hover">
                        <thead>
                            <tr>
                                <th>Evento</th>
                                <th>ID</th>
                                <th>Título</th>
                                <th style="text-align:center">Coautores</th>
                                <th style="text-align:center">Baixar</th>
                                <th style="text-align:center">Editar</th>
                                <th style="text-align:center">Excluir</th>
                                <th style="text-align:center">Pareceres</th>
                                <th style="text-align:center">Envio de Correção</th>
                                <th class="text-center">Resultado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trabalhos as $trabalho)
                                <tr>
                                    <td>{{ $trabalho->evento->nome }}</td>
                                    <td>{{ $trabalho->id }}</td>
                                    <td>
                                        @if ($trabalho->modalidade->texto)
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalVisualizarResumo_{{ $trabalho->id }}" class="text-dark fw-bold text-decoration-none" title="Clique para ver o resumo completo">
                                                {{ $trabalho->titulo }}
                                            </a>
                                        @else
                                            {{ $trabalho->titulo }}
                                        @endif
                                    </td>
                                    <td style="text-align:center">
                                        <a data-bs-toggle="modal"
                                            data-bs-target="#modalCoautoresTrabalho_{{ $trabalho->id }}"
                                            style="cursor: pointer;">
                                            <img src="{{ asset('img/icons/eye-regular.svg') }}" style="width:20px">
                                        </a>
                                    </td>
                                    
                                    <td style="text-align:center">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalDownloadTrabalho_{{ $trabalho->id }}" style="font-size: 20px; color: #114048ff;">
                                            <img class="" src="{{ asset('img/icons/file-download-solid.svg') }}" style="width:20px" title="Arquivos para Download">
                                        </a>
                                    </td>
                                    
                                    <td style="text-align:center">
                                        @if ($trabalho->modalidade->estaEmPeriodoDeSubmissao())
                                            <a href="#" onclick="return false;" data-bs-toggle="modal" data-bs-target="#modalEditarTrabalho_{{ $trabalho->id }}" style="color:#114048ff">
                                                <img class="" src="{{ asset('img/icons/edit-regular.svg') }}"
                                                    style="width:20px">
                                            </a>
                                        @endif
                                    </td>
                                    <td style="text-align:center">
                                        @if ($trabalho->modalidade->estaEmPeriodoDeSubmissao())
                                            <a href="#" onclick="return false;" data-bs-toggle="modal" data-bs-target="#modalExcluirTrabalho_{{ $trabalho->id }}" style="color:#114048ff">
                                                <img class="" src="{{ asset('img/icons/trash-alt-regular.svg') }}"
                                                    style="width:20px">
                                            </a>
                                        @endif
                                    </td>

                                    <td style="text-align:center">
                                        @foreach ($trabalho->atribuicoes as $revisor)
                                            @if (
                                                ($trabalho->atribuicoes->count() == 1 && ($trabalho->status == 'avaliado' || $trabalho->getParecerAtribuicao($revisor->user) == 'encaminhado'))
                                                || ($trabalho->atribuicoes->count() > 1 && $trabalho->getParecerAtribuicao($revisor->user) == 'encaminhado')
                                            )
                                                <a href="{{ route('user.visualizarParecer', ['eventoId' => $trabalho->evento->id, 'modalidadeId' => $trabalho->modalidadeId, 'trabalhoId' => $trabalho->id, 'revisorId' => $revisor->id, 'id' => $trabalho->id]) }}">
                                                    <img src="{{ asset('img/icons/eye-regular.svg') }}"
                                                        style="width:20px">
                                                </a>
                                            @endif
                                        @endforeach

                                        @if ($trabalho->pareceres->where('parecer_final', true)->count() > 0)
                                            <a href="#" onclick="return false;" data-bs-toggle="modal"
                                                data-bs-target="#modalparecerfinal{{ $trabalho->id }}">
                                                <img src="{{ asset('img/icons/eye-regular.svg') }}" style="width:20px"
                                                    title="Parecer final">
                                            </a>
                                            <div class="modal fade" id="modalparecerfinal{{ $trabalho->id }}"
                                                tabindex="-1"
                                                aria-labelledby="modalparecerfinal{{ $trabalho->id }}Label"
                                                aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header"
                                                            style="background-color: #114048ff; color: white;">
                                                            <h5>Parecer final</h5>
                                                            <button type="button" class="close" data-bs-dismiss="modal"
                                                                aria-label="Close" style="color: white;">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        @php
                                                            $parecer = $trabalho->pareceres
                                                                ->where('parecer_final', true)
                                                                ->first();
                                                        @endphp
                                                        <div class="modal-body">
                                                            <p class="text-left">
                                                                Resultado:
                                                                {{ $parecer->resultado == 'positivo' ? 'Aprovado' : 'Reprovado' }}
                                                            </p>
                                                            <p class="text-left">
                                                                {{ $parecer->justificativa }}
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Fechar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>

                                    <td style="text-align:center">
                                        @if ($trabalho->aprovado === true)
                                            @php
                                                $dataEnvio = $trabalho->data_correcao_submetida 
                                                    ?? optional($trabalho->arquivoCorrecao)->created_at;
                                            @endphp

                                            @if ($dataEnvio)
                                                <span class="badge bg-secondary text-wrap" style="font-size: 11px; line-height: 1.4;" title="Trabalho aprovado com correção enviada">
                                                    <i class="fas fa-lock me-1"></i> Enviada em:<br>
                                                    {{ $dataEnvio->format('d/m/Y \à\s H:i') }}
                                                </span>
                                            @else
                                                <span class="badge bg-success" style="font-size: 11px;">Aprovado</span>
                                            @endif

                                        @elseif (($trabalho->modalidade->inicioCorrecao <= $agora && $trabalho->modalidade->fimCorrecao >= $agora
                                                || $trabalho->modalidade->estaEmPeriodoExtraDeCorrecao()) 
                                                && ($trabalho->getOriginal('aprovado') === null && $trabalho->permite_correcao))
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalCorrecaoTrabalho_{{ $trabalho->id }}" 
                                            style="color:#114048ff" title="Enviar correção">
                                                <img src="{{ asset('img/icons/file-upload-solid.svg') }}" style="width:20px">
                                            </a>

                                        @elseif ($trabalho->aprovado === false)
                                            <span class="text-danger font-weight-bold" style="font-size: 12px;">Reprovado</span>

                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($trabalho->aprovado === true)
                                            <p class="py-2 px-2 bg-success text-white rounded-pill shadow">Aprovado</p>
                                        @elseif($trabalho->aprovado === false)
                                            <p class="py-2 px-2 bg-danger text-white rounded-pill shadow">Reprovado</p>
                                        @else
                                            <p class="py-2 px-2 bg-warning text-white rounded-pill shadow">Em andamento</p>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    Você não submeteu nenhum trabalho...
                @endif
            </div>
        </div>

        <br>

        <div class="row margin">
            <div class="col-sm-12 info-evento">
                <h4>Como Coautor</h4>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-12">

                @if ($trabalhosCoautor != null && count($trabalhosCoautor) > 0)
                    <table class="table table-responsive-lg table-hover">
                        <thead>
                            <tr>
                                <th>Evento</th>
                                <th>Título</th>
                                <th>Autor</th>
                                <th style="text-align:center">Baixar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trabalhosCoautor as $trabalho)
                                <tr>
                                    <td>{{ $trabalho->evento->nome }}</td>
                                    <td>{{ $trabalho->titulo }}</td>
                                    <td>{{ $trabalho->autor->name }}</td>
                                    <td style="text-align:center">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalDownloadTrabalho_{{ $trabalho->id }}" style="font-size: 20px; color: #114048ff;">
                                            <img class=""
                                                src="{{ asset('img/icons/file-download-solid.svg') }}"
                                                style="width:20px" title="Arquivos para Download">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    Você não participa como coautor em nenhum trabalho...
                @endif
            </div>
        </div>

    </div>

    @foreach ($trabalhos as $trabalho)
        <div class="modal fade" id="modalDownloadTrabalho_{{ $trabalho->id }}" tabindex="-1" role="dialog"
            aria-labelledby="modalDownloadTrabalhoLabel{{ $trabalho->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #114048ff; color: white;">
                        <h5 class="modal-title" id="modalDownloadTrabalhoLabel{{ $trabalho->id }}">Baixar - {{ $trabalho->titulo }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-3">Selecione o documento que deseja acessar ou baixar:</p>
                        
                        @php
                            $arquivoOriginal = $trabalho->arquivo()->where('versaoFinal', true)->first() ?? $trabalho->arquivo()->first();
                            $temArquivoOriginal = $arquivoOriginal != null && Storage::disk()->exists($arquivoOriginal->nome);
                            $temCorrecaoArquivo = $trabalho->arquivoCorrecao && Storage::disk()->exists($trabalho->arquivoCorrecao->caminho);
                        @endphp

                        {{-- 1. TRABALHO ORIGINAL OU RESUMO --}}
                        <div class="d-flex align-items-center justify-content-between mb-2 p-2 border rounded bg-light">
                            <h6 class="me-3 mb-0">Trabalho Original:</h6>
                            <div>
                                @if ($temArquivoOriginal)
                                    <a href="{{ route('downloadTrabalho', ['id' => $trabalho->id]) }}" target="_blank" class="btn btn-primary btn-sm d-flex align-items-center" title="Baixar primeiro envio">
                                        <img src="{{ asset('img/icons/file-download-solid.svg') }}" style="width:16px; filter: invert(1);" class="me-1"> Baixar
                                    </a>
                                @elseif ($trabalho->modalidade->texto)
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalVisualizarResumo_{{ $trabalho->id }}">
                                        Ver Resumo
                                    </button>
                                @else
                                    <span class="text-danger small">Arquivo não encontrado.</span>
                                @endif
                            </div>
                        </div>

                        {{-- 2. CORREÇÃO ENVIADA (APENAS SE A MODALIDADE FOR ARQUIVO) --}}
                        @if ($trabalho->modalidade->arquivo)
                            <div class="d-flex align-items-center justify-content-between mb-2 p-2 border rounded bg-light">
                                <h6 class="me-3 mb-0">Correção Enviada:</h6>
                                <div>
                                    @if ($temCorrecaoArquivo)
                                        <a href="{{ route('downloadCorrecao', ['id' => $trabalho->id]) }}" target="_blank" class="btn btn-success btn-sm d-flex align-items-center" title="Baixar correção enviada">
                                            <img src="{{ asset('img/icons/file-download-solid.svg') }}" style="width:16px; filter: invert(1);" class="me-1"> Baixar Correção
                                        </a>
                                    @else
                                        <span class="text-warning small">Correção ainda não submetida.</span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- 3. CARTA DE ACEITE (QUANDO O TRABALHO ESTIVER APROVADO) --}}
                        @if ($trabalho->aprovado === true)
                            <div class="d-flex align-items-center justify-content-between p-2 border rounded border-success bg-light">
                                <h6 class="me-3 mb-0 text-success font-weight-bold">Carta de Aceite:</h6>
                                <div>
                                    <a href="{{ route('cartaAceite.downloadPdf', ['codigo' => $trabalho->hash_codigo_aprovacao ?? $trabalho->id]) }}" target="_blank" class="btn btn-success">
                                        Baixar Carta de Aceite (PDF)
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="modalCoautoresTrabalho_{{ $trabalho->id }}" tabindex="-1"
            aria-labelledby="modalCoautoresTrabalho_{{ $trabalho->id }}Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #114048ff; color: white;">
                        <h5 class="modal-title">Coautores do trabalho {{ $trabalho->titulo }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                            style="color: white;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="autor" style="font-weight: bold">{{ __('Autor') }}:</label>
                        <p>{{ $trabalho->autor->name }}</p>
                        <label for="autor" style="font-weight: bold">{{ __('Coautores') }}:</label>
                        @foreach ($trabalho->coautors as $coautor)
                            <p>{{ $coautor->user->name }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @if ($trabalho->modalidade->estaEmPeriodoDeSubmissao())
            
            <div class="modal fade" id="modalEditarTrabalho_{{ $trabalho->id }}" tabindex="-1"
                aria-labelledby="modalEditarTrabalho_{{ $trabalho->id }}Label" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #114048ff; color: white;">
                            <h5 class="modal-title" id="modalEditarTrabalho_{{ $trabalho->id }}Label">Editar
                                {{ $trabalho->id }}</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                                style="color: white;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="formEditarTrab{{ $trabalho->id }}"
                                action="{{ route('editar.trabalho', ['id' => $trabalho->id]) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="modalidade{{ $trabalho->id }}"
                                    value="{{ $trabalho->modalidade->id }}">
                                @php
                                    $formSubTraba = $trabalho->evento->formSubTrab;
                                    $ordem = explode(',', $formSubTraba->ordemCampos);
                                    array_splice($ordem, 6, 0, 'midiaExtra');
                                    array_splice($ordem, 5, 0, 'apresentacao');
                                    $modalidade = $trabalho->modalidade;
                                    $areas = $trabalho->evento->areas;
                                @endphp
                                <input type="hidden" name="trabalhoEditId" value="{{ $trabalho->id }}">
                                @error('numeroMax' . $trabalho->id)
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-danger" role="alert">
                                                {{ $message }}
                                            </div>
                                        </div>
                                    </div>
                                @enderror
                                @foreach ($ordem as $indice)
                                    @if ($indice == 'etiquetatitulotrabalho')
                                        <div class="row justify-content-center">
                                            {{-- Nome Trabalho  --}}
                                            <div class="col-sm-12">
                                                <label for="nomeTrabalho_{{ $trabalho->id }}"
                                                    class="col-form-label">{{ $formSubTraba->etiquetatitulotrabalho }}</label>
                                                <input id="nomeTrabalho_{{ $trabalho->id }}" type="text"
                                                    class="form-control @error('nomeTrabalho' . $trabalho->id) is-invalid @enderror"
                                                    name="nomeTrabalho{{ $trabalho->id }}"
                                                    value="{{ old('nomeTrabalho' . $trabalho->id, $trabalho->titulo) }}"
                                                    required autocomplete="nomeTrabalho" autofocus>

                                                @error('nomeTrabalho' . $trabalho->id)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                    @if ($indice == 'etiquetacoautortrabalho')
                                        <div class="flexContainer" style="margin-top:20px">
                                            <div class="row">
                                                <div class="col">
                                                    <label><b>{{ $trabalho->evento->formSubTrab->etiquetaautortrabalho }}</b></label>
                                                </div>
                                                <div class="col mr-5">
                                                    <div class="float-right">
                                                        <a href="#" style="color: #196572ff;text-decoration: none;"
                                                            title="Clique aqui para adicionar coautor(es), se houver"
                                                            onclick="montarLinhaInput(this, {{ $trabalho->id }}, event)"
                                                            id="addCoautor_{{ $trabalho->id }}">
                                                            <img id="icone-add-coautor"
                                                                src="{{ asset('img/icons/user-plus-black-solid.svg') }}"
                                                                alt="ícone de adicionar " width="30px"
                                                                class="mb-2 m-2">{{ $trabalho->evento->formSubTrab->etiquetacoautortrabalho }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="coautores{{ $trabalho->id }}" class="flexContainer ">
                                                @if (old('nomeCoautor_' . $trabalho->id) != null)
                                                    @foreach (old('nomeCoautor_' . $trabalho->id) as $i => $nomeCoautor)
                                                        <div class="item card mt-0">
                                                            <div class="row card-body">
                                                                <div class="col-sm-4">
                                                                    <label>E-mail</label>
                                                                    <input type="email" style="margin-bottom:10px"
                                                                        value="{{ old('emailCoautor_' . $trabalho->id)[$i] }}"
                                                                        class="form-control emailCoautor"
                                                                        name="emailCoautor_{{ $trabalho->id }}[]"
                                                                        placeholder="E-mail">
                                                                </div>
                                                                <div class="col-sm-5">
                                                                    <label>Nome Completo</label>
                                                                    <input type="text" style="margin-bottom:10px"
                                                                        value="{{ $nomeCoautor }}"
                                                                        class="form-control emailCoautor"
                                                                        name="nomeCoautor_{{ $trabalho->id }}[]"
                                                                        placeholder="Nome">
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <a style="color: #d30909;" href="#"
                                                                        onclick="deletarCoautor(this, {{ $trabalho->id }}, event)"
                                                                        class="delete pr-2">
                                                                        <img src="{{ asset('img/icons/trash-alt-regular.svg') }}"
                                                                            class="icon-card" width="24"
                                                                            alt="Remover">
                                                                    </a>
                                                                    <a href="#"
                                                                        onclick="mover(this.parentElement.parentElement.parentElement, 1, {{ $trabalho->id }}, event)">
                                                                        <img src="{{ asset('img/icons/sobe.png') }}"
                                                                            class="icon-card" width="24"
                                                                            alt="Subir">
                                                                    </a>
                                                                    <a href="#"
                                                                        onclick="mover(this.parentElement.parentElement.parentElement, 0, {{ $trabalho->id }}, event)">
                                                                        <img src="{{ asset('img/icons/desce.png') }}"
                                                                            class="icon-card" width="24"
                                                                            alt="Descer">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="item card mt-0">
                                                        <div class="row card-body">
                                                            <div class="col-sm-4">
                                                                <label>E-mail</label>
                                                                <input type="email" style="margin-bottom:10px"
                                                                    value="{{ $trabalho->autor->email }}"
                                                                    oninput="buscarEmail(this)"
                                                                    class="form-control emailCoautor"
                                                                    name="emailCoautor_{{ $trabalho->id }}[]"
                                                                    placeholder="E-mail" required>
                                                            </div>
                                                            <div class="col-sm-5">
                                                                <label>Nome Completo</label>
                                                                <input type="text" style="margin-bottom:10px"
                                                                    value="{{ $trabalho->autor->name }}"
                                                                    class="form-control emailCoautor"
                                                                    name="nomeCoautor_{{ $trabalho->id }}[]"
                                                                    placeholder="Nome" required>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <a style="color: #d30909;" href="#"
                                                                    onclick="deletarCoautor(this, {{ $trabalho->id }}, event)"
                                                                    class="delete pr-2">
                                                                    <img src="{{ asset('img/icons/trash-alt-regular.svg') }}"
                                                                        class="icon-card" width="24" alt="Remover">
                                                                </a>
                                                                <a href="#"
                                                                    onclick="mover(this.parentElement.parentElement.parentElement, 1, {{ $trabalho->id }}, event)">
                                                                    <img src="{{ asset('img/icons/sobe.png') }}"
                                                                        class="icon-card" width="24" alt="Subir">
                                                                </a>
                                                                <a href="#"
                                                                    onclick="mover(this.parentElement.parentElement.parentElement, 0, {{ $trabalho->id }}, event)">
                                                                    <img src="{{ asset('img/icons/desce.png') }}"
                                                                        class="icon-card" width="24" alt="Descer">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if (!$trabalho->coautors->isEmpty())
                                                        <label id="title-coautores{{ $trabalho->id }}"
                                                            style="margin-top:20px"><b>{{ $trabalho->evento->formSubTrab->etiquetacoautortrabalho }}</b></label>
                                                    @endif
                                                    @foreach ($trabalho->coautors as $i => $coautor)
                                                        <div class="item card mt-0">
                                                            <div class="row card-body">
                                                                <div class="col-sm-4">
                                                                    <label>E-mail</label>
                                                                    <input type="email" style="margin-bottom:10px"
                                                                        value="{{ $coautor->user->email }}"
                                                                        oninput="buscarEmail(this)"
                                                                        class="form-control emailCoautor"
                                                                        name="emailCoautor_{{ $trabalho->id }}[]"
                                                                        placeholder="E-mail" required>
                                                                </div>
                                                                <div class="col-sm-5">
                                                                    <label>Nome Completo</label>
                                                                    <input type="text" style="margin-bottom:10px"
                                                                        value="{{ $coautor->user->name }}"
                                                                        class="form-control emailCoautor"
                                                                        name="nomeCoautor_{{ $trabalho->id }}[]"
                                                                        placeholder="Nome" required>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <a h style="color: #d30909;" href="#"
                                                                        onclick="deletarCoautor(this, {{ $trabalho->id }}, event)"
                                                                        class="delete pr-2">
                                                                        <img src="{{ asset('img/icons/trash-alt-regular.svg') }}"
                                                                            class="icon-card" width="24"
                                                                            alt="Remover">
                                                                    </a>
                                                                    <a href="#"
                                                                        onclick="mover(this.parentElement.parentElement.parentElement, 1, {{ $trabalho->id }}, event)">
                                                                        <img src="{{ asset('img/icons/sobe.png') }}"
                                                                            class="icon-card" width="24"
                                                                            alt="Subir">
                                                                    </a>
                                                                    <a href="#"
                                                                        onclick="mover(this.parentElement.parentElement.parentElement, 0, {{ $trabalho->id }}, event)">
                                                                        <img src="{{ asset('img/icons/desce.png') }}"
                                                                            class="icon-card" width="24"
                                                                            alt="Descer">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    @if ($modalidade->texto && $indice == 'etiquetaresumotrabalho')
                                        @if ($modalidade->caracteres == true)
                                            <div class="row justify-content-center">
                                                <div class="col-sm-12">
                                                    <label for="resumo_{{ $trabalho->id }}"
                                                        class="col-form-label">{{ $formSubTraba->etiquetaresumotrabalho }}</label>
                                                    <textarea id="resumo_{{ $trabalho->id }}"
                                                        class="char-count form-control @error('resumo' . $trabalho->id) is-invalid @enderror" data-ls-module="charCounter"
                                                        minlength="{{ $modalidade->mincaracteres }}" maxlength="{{ $modalidade->maxcaracteres }}"
                                                        name="resumo{{ $trabalho->id }}" autocomplete="resumo" autofocusrows="5">{{ old('resumo' . $trabalho->id, $trabalho->resumo) }}</textarea>
                                                    <p class="text-muted"><small><span
                                                                id="resumo{{ $trabalho->id }}">{{ strlen($trabalho->resumo) }}</span></small>
                                                        - Min Caracteres: {{ $modalidade->mincaracteres }} - Max
                                                        Caracteres: {{ $modalidade->maxcaracteres }}</p>
                                                    @error('resumo' . $trabalho->id)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror

                                                </div>
                                            </div>
                                        @elseif ($modalidade->palavras == true)
                                            <div class="row justify-content-center">
                                                <div class="col-sm-12">
                                                    <label for="resumo_{{ $trabalho->id }}"
                                                        class="col-form-label">{{ $formSubTraba->etiquetaresumotrabalho }}</label>
                                                    <textarea id="resumo_{{ $trabalho->id }}"
                                                        class="form-control palavra @error('resumo' . $trabalho->id) is-invalid @enderror"
                                                        name="resumo{{ $trabalho->id }}" autocomplete="resumo" autofocusrows="5">{{ old('resumo' . $trabalho->id, $trabalho->resumo) }}</textarea>
                                                    <p class="text-muted"><small><span
                                                                id="resumo{{ $trabalho->id }}">{{ count(explode(' ', $trabalho->resumo)) }}</span></small>
                                                        - Min Palavras: {{ $modalidade->minpalavras }} - Max Palavras:
                                                        {{ $modalidade->maxpalavras }}</p>
                                                    @error('resumo' . $trabalho->id)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror

                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                    @if ($indice == 'etiquetaareatrabalho')
                                        <div class="row justify-content-center">
                                            <div class="col-sm-12">
                                                <label for="area_{{ $trabalho->id }}"
                                                    class="col-form-label">{{ $formSubTraba->etiquetaareatrabalho }}</label>
                                                <select id="area_{{ $trabalho->id }}"
                                                    class="form-control @error('area' . $trabalho->id) is-invalid @enderror"
                                                    name="area{{ $trabalho->id }}" required>
                                                    <option value="" disabled selected hidden>-- Área --</option>
                                                    {{-- Apenas um teste abaixo --}}
                                                    @if (old('area' . $trabalho->id) != null)
                                                        @foreach ($areas as $area)
                                                            <option value="{{ $area->id }}"
                                                                @if (old('area' . $trabalho->id) == $area->id) selected @endif>
                                                                {{ $area->nome }}</option>
                                                        @endforeach
                                                    @else
                                                        @foreach ($areas as $area)
                                                            <option value="{{ $area->id }}"
                                                                @if ($trabalho->areaId == $area->id) selected @endif>
                                                                {{ $area->nome }}</option>
                                                        @endforeach
                                                    @endif

                                                </select>
                                                @error('area' . $trabalho->id)
                                                    <span class="invalid-feedback" role="alert"
                                                        style="overflow: visible; display:block">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                    @if ($indice == 'apresentacao')
                                        @if ($trabalho->modalidade->apresentacao)
                                            <div class="row justify-content-center mt-4">
                                                <div class="col-sm-12">
                                                    <label for="area"
                                                        class="col-form-label"><strong>{{ __('Forma de apresentação do trabalho') }}</strong>
                                                    </label>
                                                    <select name="tipo_apresentacao" id="tipo_apresentacao"
                                                        class="form-control @error('tipo_apresentacao') is-invalid @enderror"
                                                        required>
                                                        <option value="" selected disabled>
                                                            {{ __('-- Selecione a forma de apresentação do trabalho --') }}
                                                        </option>
                                                        @foreach ($trabalho->modalidade->tiposApresentacao as $tipo)
                                                            <option @if (old('tipo_apresentacao') == $tipo->tipo || $trabalho->tipo_apresentacao == $tipo->tipo) selected @endif
                                                                value="{{ $tipo->tipo }}">{{ __($tipo->tipo) }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    @error('tipo_apresentacao')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                    @if ($indice == 'midiaExtra')
                                        <div class="row justify-content-center">
                                            @foreach ($modalidade->midiasExtra as $midia)
                                                <div class="col-sm-12" style="margin-top: 20px;">
                                                    <label for="{{ $midia->hyphenizeNome() }}"
                                                        class="col-form-label"><strong>{{ $midia->nome }}</strong>
                                                    </label>
                                                    <a
                                                        href="{{ route('downloadMidiaExtra', ['id' => $trabalho->id, 'id_midia' => $midia->id]) }}">Arquivo
                                                        atual</a>
                                                    <br>
                                                    <small>Para trocar o arquivo envie um novo.</small>
                                                    <div class="custom-file">
                                                        <input type="file" class="filestyle"
                                                            data-placeholder="Nenhum arquivo" data-text="Selecionar"
                                                            data-btnClass="btn-primary-lmts"
                                                            name="{{ $midia->hyphenizeNome() }}">
                                                    </div>
                                                    <small><strong>Extensão de arquivos aceitas:</strong>
                                                        @if ($midia->pdf == true)
                                                            <span> / ".pdf"</span>
                                                        @endif
                                                        @if ($midia->jpg == true)
                                                            <span> / ".jpg"</span>
                                                        @endif
                                                        @if ($midia->jpeg == true)
                                                            <span> / ".jpeg"</span>
                                                        @endif
                                                        @if ($midia->png == true)
                                                            <span> / ".png"</span>
                                                        @endif
                                                        @if ($midia->docx == true)
                                                            <span> / ".docx"</span>
                                                        @endif
                                                        @if ($midia->odt == true)
                                                            <span> / ".odt"</span>
                                                        @endif
                                                        @if ($midia->zip == true)
                                                            <span> / ".zip"</span>
                                                        @endif
                                                        @if ($midia->svg == true)
                                                            <span> / ".svg"</span>
                                                        @endif
                                                        @if ($midia->mp4 == true)
                                                            <span> / ".mp4"</span>
                                                        @endif
                                                        @if ($midia->mp3 == true)
                                                            <span> / ".mp3"</span>
                                                        @endif.
                                                    </small>
                                                    @error($midia->nome)
                                                        <span class="invalid-feedback" role="alert"
                                                            style="overflow: visible; display:block">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if ($indice == 'etiquetauploadtrabalho')
                                        <div class="row justify-content-center">
                                            {{-- Submeter trabalho --}}

                                            @if ($modalidade->arquivo == true)
                                                <div class="col-sm-12" style="margin-top: 20px;">
                                                    <label for="nomeTrabalho"
                                                        class="col-form-label">{{ $formSubTraba->etiquetauploadtrabalho }}:</label>
                                                    <a href="{{ route('downloadTrabalho', ['id' => $trabalho->id]) }}">Arquivo
                                                        atual</a>
                                                    <br>

                                                    @if ($modalidade->submissaoUnica == true)
                                                        <small>Não é possível reenviar um trabalho nesta modalidade.</small>
                                                    @else
                                                        <small>Para trocar o arquivo envie um novo.</small>
                                                        <div class="custom-file">
                                                            <input type="file" class="filestyle"
                                                                data-placeholder="Nenhum arquivo" data-text="Selecionar"
                                                                data-btnClass="btn-primary-lmts"
                                                                name="arquivo{{ $trabalho->id }}">
                                                        </div>
                                                        <small>Arquivos aceitos nos formatos
                                                            @if ($trabalho->modalidade->pdf == true)
                                                                <span> - pdf</span>
                                                            @endif
                                                            @if ($trabalho->modalidade->jpg == true)
                                                                <span> - jpg</span>
                                                            @endif
                                                            @if ($trabalho->modalidade->jpeg == true)
                                                                <span> - jpeg</span>
                                                            @endif
                                                            @if ($trabalho->modalidade->png == true)
                                                                <span> - png</span>
                                                            @endif
                                                            @if ($trabalho->modalidade->docx == true)
                                                                <span> - docx</span>
                                                            @endif
                                                            @if ($trabalho->modalidade->odt == true)
                                                                <span> - odt</span>
                                                            @endif
                                                            @if ($trabalho->modalidade->zip == true)
                                                                <span> - zip</span>
                                                            @endif
                                                            @if ($trabalho->modalidade->svg == true)
                                                                <span> - svg</span>
                                                            @endif
                                                            @if ($trabalho->modalidade->mp4 == true)
                                                                <span> - mp4</span>
                                                            @endif
                                                            @if ($trabalho->modalidade->mp3 == true)
                                                                <span> - mp3</span>
                                                            @endif.
                                                        </small>
                                                    @endif



                                                    @error('arquivo' . $trabalho->id)
                                                        <span class="invalid-feedback" role="alert"
                                                            style="overflow: visible; display:block">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    @if ($indice == 'etiquetacampoextra1')
                                        @if ($formSubTraba->checkcampoextra1 == true)
                                            @if ($formSubTraba->tipocampoextra1 == 'textosimples')
                                                {{-- Texto Simples --}}
                                                <div class="row justify-content-center">
                                                    {{-- Nome Trabalho  --}}
                                                    <div class="col-sm-12">
                                                        <label for="campoextra1simples_{{ $trabalho->id }}"
                                                            class="col-form-label">{{ $formSubTraba->etiquetacampoextra1 }}:</label>
                                                        <input id="campoextra1simples_{{ $trabalho->id }}"
                                                            type="text"
                                                            class="form-control @error('campoextra1simples') is-invalid @enderror"
                                                            name="campoextra1simples"
                                                            value="{{ old('campoextra1simples') }}" required
                                                            autocomplete="campoextra1simples" autofocus>

                                                        @error('campoextra1simples')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @elseif ($formSubTraba->tipocampoextra1 == 'textogrande')
                                                {{-- Texto Grande --}}
                                                <div class="row justify-content-center">
                                                    <div class="col-sm-12">
                                                        <label for="campoextra1grande"
                                                            class="col-form-label">{{ $formSubTraba->etiquetacampoextra1 }}:</label>
                                                        <textarea id="campoextra1grande" type="text"
                                                            class="form-control @error('campoextra1grande') is-invalid @enderror" name="campoextra1grande"
                                                            value="{{ old('campoextra1grande') }}" required autocomplete="campoextra1grande" autofocus></textarea>

                                                        @error('campoextra1grande')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @elseif ($formSubTraba->tipocampoextra1 == 'upload')
                                                <div class="col-sm-12" style="margin-top: 20px;">
                                                    <label for="campoextra1arquivo"
                                                        class="col-form-label">{{ $formSubTraba->etiquetacampoextra1 }}:</label>

                                                    <div class="custom-file">
                                                        <input type="file" class="filestyle"
                                                            data-placeholder="Nenhum arquivo" data-text="Selecionar"
                                                            data-btnClass="btn-primary-lmts" name="campoextra1arquivo"
                                                            required>
                                                    </div>
                                                    <small>Algum texto aqui?</small>
                                                    @error('campoextra1arquivo')
                                                        <span class="invalid-feedback" role="alert"
                                                            style="overflow: visible; display:block">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                    @if ($indice == 'etiquetacampoextra2')
                                        @if ($formSubTraba->checkcampoextra2 == true)
                                            @if ($formSubTraba->tipocampoextra2 == 'textosimples')
                                                <div class="row justify-content-center">
                                                    {{-- Nome Trabalho  --}}
                                                    <div class="col-sm-12">
                                                        <label for="campoextra2simples"
                                                            class="col-form-label">{{ $formSubTraba->etiquetacampoextra2 }}:</label>
                                                        <input id="campoextra2simples" type="text"
                                                            class="form-control @error('campoextra2simples') is-invalid @enderror"
                                                            name="campoextra2simples"
                                                            value="{{ old('campoextra2simples') }}" required
                                                            autocomplete="campoextra2simples" autofocus>

                                                        @error('campoextra2simples')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @elseif ($formSubTraba->tipocampoextra2 == 'textogrande')
                                                <div class="row justify-content-center">
                                                    {{-- Nome Trabalho  --}}
                                                    <div class="col-sm-12">
                                                        <label for="campoextra2grande"
                                                            class="col-form-label">{{ $formSubTraba->etiquetacampoextra2 }}:</label>
                                                        <textarea id="campoextra2grande" type="text"
                                                            class="form-control @error('campoextra2grande') is-invalid @enderror" name="campoextra2grande"
                                                            value="{{ old('campoextra2grande') }}" required autocomplete="campoextra2grande" autofocus></textarea>

                                                        @error('campoextra2grande')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @elseif ($formSubTraba->tipocampoextra2 == 'upload')
                                                <div class="col-sm-12" style="margin-top: 20px;">
                                                    <label for="campoextra2arquivo"
                                                        class="col-form-label">{{ $formSubTraba->etiquetacampoextra2 }}:</label>

                                                    <div class="custom-file">
                                                        <input type="file" class="filestyle"
                                                            data-placeholder="Nenhum arquivo" data-text="Selecionar"
                                                            data-btnClass="btn-primary-lmts" name="campoextra2arquivo"
                                                            required>
                                                    </div>
                                                    <small>Algum texto aqui?</small>
                                                    @error('campoextra2arquivo')
                                                        <span class="invalid-feedback" role="alert"
                                                            style="overflow: visible; display:block">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                    @if ($indice == 'etiquetacampoextra3')
                                        @if ($formSubTraba->checkcampoextra3 == true)
                                            @if ($formSubTraba->tipocampoextra3 == 'textosimples')
                                                <div class="row justify-content-center">
                                                    {{-- Nome Trabalho  --}}
                                                    <div class="col-sm-12">
                                                        <label for="campoextra3simples"
                                                            class="col-form-label">{{ $formSubTraba->etiquetacampoextra3 }}:</label>
                                                        <input id="campoextra3simples" type="text"
                                                            class="form-control @error('campoextra3simples') is-invalid @enderror"
                                                            name="campoextra3simples"
                                                            value="{{ old('campoextra3simples') }}" required
                                                            autocomplete="campoextra3simples" autofocus>

                                                        @error('campoextra3simples')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @elseif ($formSubTraba->tipocampoextra3 == 'textogrande')
                                                <div class="row justify-content-center">
                                                    {{-- Nome Trabalho  --}}
                                                    <div class="col-sm-12">
                                                        <label for="campoextra3grande"
                                                            class="col-form-label">{{ $formSubTraba->etiquetacampoextra3 }}:</label>
                                                        <textarea id="campoextra3grande" type="text"
                                                            class="form-control @error('campoextra3grande') is-invalid @enderror" name="campoextra3grande"
                                                            value="{{ old('campoextra3grande') }}" required autocomplete="campoextra3grande" autofocus></textarea>

                                                        @error('campoextra3grande')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @elseif ($formSubTraba->tipocampoextra3 == 'upload')
                                                {{-- Arquivo de Regras  --}}
                                                <div class="col-sm-12" style="margin-top: 20px;">
                                                    <label for="campoextra3arquivo"
                                                        class="col-form-label">{{ $formSubTraba->etiquetacampoextra3 }}:</label>

                                                    <div class="custom-file">
                                                        <input type="file" class="filestyle"
                                                            data-placeholder="Nenhum arquivo" data-text="Selecionar"
                                                            data-btnClass="btn-primary-lmts" name="campoextra3arquivo"
                                                            required>
                                                    </div>
                                                    <small>Algum texto aqui?</small>
                                                    @error('campoextra3arquivo')
                                                        <span class="invalid-feedback" role="alert"
                                                            style="overflow: visible; display:block">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                    @if ($indice == 'etiquetacampoextra4')
                                        @if ($formSubTraba->checkcampoextra4 == true)
                                            @if ($formSubTraba->tipocampoextra4 == 'textosimples')
                                                <div class="row justify-content-center">
                                                    {{-- Nome Trabalho  --}}
                                                    <div class="col-sm-12">
                                                        <label for="campoextra4simples"
                                                            class="col-form-label">{{ $formSubTraba->etiquetacampoextra4 }}:</label>
                                                        <input id="campoextra4simples" type="text"
                                                            class="form-control @error('campoextra4simples') is-invalid @enderror"
                                                            name="campoextra4simples"
                                                            value="{{ old('campoextra4simples') }}" required
                                                            autocomplete="campoextra4simples" autofocus>

                                                        @error('campoextra4simples')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @elseif ($formSubTraba->tipocampoextra4 == 'textogrande')
                                                <div class="row justify-content-center">
                                                    {{-- Nome Trabalho  --}}
                                                    <div class="col-sm-12">
                                                        <label for="campoextra4grande"
                                                            class="col-form-label">{{ $formSubTraba->etiquetacampoextra4 }}:</label>
                                                        <textarea id="campoextra4grande" type="text"
                                                            class="form-control @error('campoextra4grande') is-invalid @enderror" name="campoextra4grande"
                                                            value="{{ old('campoextra4grande') }}" required autocomplete="campoextra4grande" autofocus></textarea>

                                                        @error('campoextra4grande')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @elseif ($formSubTraba->tipocampoextra4 == 'upload')
                                                {{-- Arquivo de Regras  --}}
                                                <div class="col-sm-12" style="margin-top: 20px;">
                                                    <label for="campoextra4arquivo"
                                                        class="col-form-label">{{ $formSubTraba->etiquetacampoextra4 }}:</label>

                                                    <div class="custom-file">
                                                        <input type="file" class="filestyle"
                                                            data-placeholder="Nenhum arquivo" data-text="Selecionar"
                                                            data-btnClass="btn-primary-lmts" name="campoextra4arquivo"
                                                            required>
                                                    </div>
                                                    <small>Algum texto aqui?</small>
                                                    @error('campoextra4arquivo')
                                                        <span class="invalid-feedback" role="alert"
                                                            style="overflow: visible; display:block">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                    @if ($indice == 'etiquetacampoextra5')
                                        @if ($formSubTraba->checkcampoextra5 == true)
                                            @if ($formSubTraba->tipocampoextra5 == 'textosimples')
                                                <div class="row justify-content-center">
                                                    {{-- Nome Trabalho  --}}
                                                    <div class="col-sm-12">
                                                        <label for="campoextra5simples"
                                                            class="col-form-label">{{ $formSubTraba->etiquetacampoextra5 }}:</label>
                                                        <input id="campoextra5simples" type="text"
                                                            class="form-control @error('campoextra5simples') is-invalid @enderror"
                                                            name="campoextra5simples"
                                                            value="{{ old('campoextra5simples') }}" required
                                                            autocomplete="campoextra5simples" autofocus>

                                                        @error('campoextra5simples')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @elseif ($formSubTraba->tipocampoextra5 == 'textogrande')
                                                <div class="row justify-content-center">
                                                    {{-- Nome Trabalho  --}}
                                                    <div class="col-sm-12">
                                                        <label for="campoextra5"
                                                            class="col-form-label">{{ $formSubTraba->etiquetacampoextra5 }}:</label>
                                                        <textarea id="campoextra5grande" type="text"
                                                            class="form-control @error('campoextra5grande') is-invalid @enderror" name="campoextra5grande"
                                                            value="{{ old('campoextra5grande') }}" required autocomplete="campoextra5grande" autofocus></textarea>

                                                        @error('campoextra5grande')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @elseif ($formSubTraba->tipocampoextra5 == 'upload')
                                                {{-- Arquivo de Regras  --}}
                                                <div class="col-sm-12" style="margin-top: 20px;">
                                                    <label for="campoextra5arquivo"
                                                        class="col-form-label">{{ $formSubTraba->etiquetacampoextra5 }}:</label>

                                                    <div class="custom-file">
                                                        <input type="file" class="filestyle"
                                                            data-placeholder="Nenhum arquivo" data-text="Selecionar"
                                                            data-btnClass="btn-primary-lmts" name="campoextra5arquivo"
                                                            required>
                                                    </div>
                                                    <small>Algum texto aqui?</small>
                                                    @error('campoextra5arquivo')
                                                        <span class="invalid-feedback" role="alert"
                                                            style="overflow: visible; display:block">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                @endforeach
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary"
                                form="formEditarTrab{{ $trabalho->id }}">Salvar</button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        {{-- MODAL CORREÇÃO DO TRABALHO --}}
        @if (
        ($trabalho->modalidade->inicioCorrecao <= $agora && $agora <= $trabalho->modalidade->fimCorrecao) ||
        $trabalho->modalidade->estaEmPeriodoExtraDeCorrecao())
            <div class="modal fade" id="modalCorrecaoTrabalho_{{ $trabalho->id }}" tabindex="-1"
                aria-labelledby="modalCorrecaoTrabalho_{{ $trabalho->id }}Label" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #114048ff; color: white;">
                            <h5 class="modal-title" id="modalCorrecaoTrabalho_{{ $trabalho->id }}Label">
                                Correção do trabalho: {{ $trabalho->titulo }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
                        </div>

                        <div class="modal-body">
                            <form id="formCorrecaoTrabalho{{ $trabalho->id }}"
                                action="{{ route('trabalho.correcao', ['id' => $trabalho->id]) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                @php
                                    $formSubTraba = $trabalho->evento->formSubTrab;
                                    $modalidade = $trabalho->modalidade;
                                @endphp

                                <input type="hidden" name="trabalhoCorrecaoId" value="{{ $trabalho->id }}">

                                <div class="row justify-content-center mb-3">
                                    <div class="col-sm-12">
                                        <label for="tituloCorrecao_{{ $trabalho->id }}" class="col-form-label font-weight-bold">
                                            {{ $formSubTraba->etiquetatitulotrabalho ?? 'Título' }}: <span class="text-danger">*</span>
                                        </label>
                                        <input id="tituloCorrecao_{{ $trabalho->id }}" type="text" 
                                            class="form-control" name="tituloCorrecao" 
                                            value="{{ old('tituloCorrecao', $trabalho->titulo) }}" required>
                                    </div>
                                </div>

                                {{-- SE A MODALIDADE FOR VIA TEXTO --}}
                                @if ($modalidade->texto)
                                    <div class="row justify-content-center mb-3">
                                        <div class="col-sm-12">
                                            <label for="resumoCorrecao_{{ $trabalho->id }}" class="col-form-label font-weight-bold">
                                                {{ $formSubTraba->etiquetaresumotrabalho ?? 'Resumo / Texto Corrigido' }}: <span class="text-danger">*</span>
                                            </label>

                                            @if ($modalidade->caracteres)
                                                <textarea id="resumoCorrecao_{{ $trabalho->id }}"
                                                    class="char-count form-control @error('resumoCorrecao') is-invalid @enderror"
                                                    name="resumoCorrecao"
                                                    rows="8"
                                                    minlength="{{ $modalidade->mincaracteres }}"
                                                    maxlength="{{ $modalidade->maxcaracteres }}"
                                                    required>{{ old('resumoCorrecao', $trabalho->resumo) }}</textarea>
                                                
                                                <p class="text-muted"><small>
                                                    <span id="resumoCorrecao_{{ $trabalho->id }}_count">{{ strlen($trabalho->resumo) }}</span> caracteres 
                                                    (Mínimo: {{ $modalidade->mincaracteres }} | Máximo: {{ $modalidade->maxcaracteres }})
                                                </small></p>

                                            @elseif ($modalidade->palavras)
                                                <textarea id="resumoCorrecao_{{ $trabalho->id }}"
                                                    class="palavra form-control @error('resumoCorrecao') is-invalid @enderror"
                                                    name="resumoCorrecao"
                                                    rows="8"
                                                    required>{{ old('resumoCorrecao', $trabalho->resumo) }}</textarea>

                                                <p class="text-muted"><small>
                                                    <span id="resumoCorrecao_{{ $trabalho->id }}_count">{{ count(explode(' ', trim($trabalho->resumo))) }}</span> palavras 
                                                    (Mínimo: {{ $modalidade->minpalavras }} | Máximo: {{ $modalidade->maxpalavras }})
                                                </small></p>
                                            @else
                                                <textarea id="resumoCorrecao_{{ $trabalho->id }}"
                                                    class="form-control @error('resumoCorrecao') is-invalid @enderror"
                                                    name="resumoCorrecao"
                                                    rows="8"
                                                    required>{{ old('resumoCorrecao', $trabalho->resumo) }}</textarea>
                                            @endif

                                            @error('resumoCorrecao')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                @endif

                                {{-- SE A MODALIDADE PERMITIR ARQUIVO --}}
                                @if ($modalidade->arquivo)
                                    <div class="row justify-content-center mb-3">
                                        <div class="col-sm-12">
                                            <label for="arquivoCorrecao_{{ $trabalho->id }}" class="col-form-label font-weight-bold">
                                                Upload do Arquivo de Correção:
                                            </label>

                                            @if ($trabalho->arquivoCorrecao()->first() != null)
                                                <div class="mb-2">
                                                    <a href="{{ route('downloadCorrecao', ['id' => $trabalho->id]) }}" class="btn btn-sm btn-outline-info">
                                                        <img src="{{ asset('img/icons/file-download-solid.svg') }}" style="width:14px"> Baixar arquivo de correção enviado anteriormente
                                                    </a>
                                                    <br>
                                                    <small class="text-muted">Envie um novo arquivo se desejar substituir o atual.</small>
                                                </div>
                                            @endif

                                            <div class="custom-file">
                                                <input type="file" class="form-control @error('arquivoCorrecao') is-invalid @enderror"
                                                    id="arquivoCorrecao_{{ $trabalho->id }}"
                                                    name="arquivoCorrecao"
                                                    {{-- Se for modalidade puramente de arquivo e ainda não enviou nenhum, torna o input obrigatório --}}
                                                    {{ !$modalidade->texto && $trabalho->arquivoCorrecao()->first() == null ? 'required' : '' }}>
                                            </div>

                                            <small class="text-muted">Extensões aceitas:
                                                @if ($modalidade->pdf) - pdf @endif
                                                @if ($modalidade->jpg) - jpg @endif
                                                @if ($modalidade->jpeg) - jpeg @endif
                                                @if ($modalidade->png) - png @endif
                                                @if ($modalidade->docx) - docx @endif
                                                @if ($modalidade->odt) - odt @endif
                                                @if ($modalidade->zip) - zip @endif
                                                @if ($modalidade->svg) - svg @endif
                                            </small>

                                            @error('arquivoCorrecao')
                                                <span class="invalid-feedback" role="alert" style="display: block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                @endif

                                <div class="card mb-3 p-3 bg-light">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0 font-weight-bold">Autores e Coautores (use as setas para ordenar):</h6>
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                onclick="montarLinhaInputCorrecao({{ $trabalho->id }}, event)">
                                            + Adicionar Coautor
                                        </button>
                                    </div>

                                    {{-- ID exclusivo para a correcao: coautoresCorrecao_ID --}}
                                    <div id="coautoresCorrecao_{{ $trabalho->id }}" class="flexContainer">
                                        {{-- Autor Principal (Posição 0 fixa) --}}
                                        <div class="item card mt-1">
                                            <div class="row card-body p-2 align-items-center">
                                                <div class="col-sm-5">
                                                    <label class="small mb-1">E-mail do Autor</label>
                                                    <input type="email" class="form-control form-control-sm" 
                                                        name="emailCoautor_{{ $trabalho->id }}[]" 
                                                        value="{{ $trabalho->autor->email }}" required>
                                                </div>
                                                <div class="col-sm-5">
                                                    <label class="small mb-1">Nome do Autor</label>
                                                    <input type="text" class="form-control form-control-sm" 
                                                        name="nomeCoautor_{{ $trabalho->id }}[]" 
                                                        value="{{ $trabalho->autor->name }}" required>
                                                </div>
                                                <div class="col-sm-2 text-center mt-3">
                                                    <span class="badge bg-primary">Autor(a)</span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Lista de Coautores ordenados --}}
                                        @foreach ($trabalho->coautors->sortBy('ordem') as $coautor)
                                            <div class="item card mt-2">
                                                <div class="row card-body p-2 align-items-center">
                                                    <div class="col-sm-5">
                                                        <label class="small mb-1">E-mail</label>
                                                        <input type="email" class="form-control form-control-sm emailCoautor" 
                                                            name="emailCoautor_{{ $trabalho->id }}[]" 
                                                            value="{{ $coautor->user->email }}" oninput="buscarEmail(this)" required>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <label class="small mb-1">Nome Completo</label>
                                                        <input type="text" class="form-control form-control-sm" 
                                                            name="nomeCoautor_{{ $trabalho->id }}[]" 
                                                            value="{{ $coautor->user->name }}" required>
                                                    </div>
                                                    <div class="col-sm-2 d-flex align-items-center justify-content-around mt-3">
                                                        <a href="#" style="color: #d30909;" onclick="deletarCoautor(this, event)" title="Remover">
                                                            <img src="{{ asset('img/icons/trash-alt-regular.svg') }}" width="18">
                                                        </a>
                                                        <a href="#" onclick="moverCoautor(this, 1, event)" title="Subir">
                                                            <img src="{{ asset('img/icons/sobe.png') }}" width="18">
                                                        </a>
                                                        <a href="#" onclick="moverCoautor(this, 0, event)" title="Descer">
                                                            <img src="{{ asset('img/icons/desce.png') }}" width="18">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </form>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary" form="formCorrecaoTrabalho{{ $trabalho->id }}">
                                Enviar correção
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    {{-- MODAL PARA VISUALIZAR O RESUMO COMPLETO E SEUS COAUTORES --}}
    @foreach ($trabalhos as $trabalho)
        @if ($trabalho->modalidade->texto || !empty($trabalho->resumo))
            <div class="modal fade" id="modalVisualizarResumo_{{ $trabalho->id }}" tabindex="-1" aria-labelledby="modalVisualizarResumoLabel{{ $trabalho->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #114048ff; color: white;">
                            <h5 class="modal-title" id="modalVisualizarResumoLabel{{ $trabalho->id }}">{{ $trabalho->titulo }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start">
                            <div class="mb-3 p-3 bg-light border rounded">
                                <p class="mb-1"><strong>Autor(a) Principal:</strong> {{ $trabalho->autor->name }} <span class="text-muted">({{ $trabalho->autor->email }})</span></p>
                                @if ($trabalho->orientador)
                                    <p class="mb-1"><strong>Orientador(a):</strong> {{ $trabalho->orientador->name }} <span class="text-muted">({{ $trabalho->orientador->email }})</span></p>
                                @endif
                                <p class="mb-1"><strong>Coautores(as):</strong></p>
                                @if ($trabalho->coautors->isNotEmpty())
                                    <ol class="mb-0 ps-3">
                                        @foreach ($trabalho->coautors->sortBy('ordem') as $coautor)
                                            <li>{{ $coautor->user->name ?? 'Sem nome' }} <span class="text-muted">({{ $coautor->user->email ?? 'Sem e-mail' }})</span></li>
                                        @endforeach
                                    </ol>
                                @else
                                    <span class="text-muted">Nenhum coautor cadastrado.</span>
                                @endif
                            </div>

                            <h6 class="fw-bold text-dark">Resumo:</h6>
                            <div class="p-3 bg-white border rounded shadow-sm" style="white-space: pre-wrap; font-size: 14px; line-height: 1.6;">{{ $trabalho->resumo }}</div>

                            @if ($trabalho->data_correcao_submetida)
                                <div class="mt-2 text-end text-muted small">
                                    <em>Última correção submetida em: {{ $trabalho->data_correcao_submetida->format('d/m/Y \à\s H:i') }}</em>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            @if ($trabalho->aprovado === true)
                                <a href="{{ route('cartaAceite.downloadPdf', ['codigo' => $trabalho->hash_codigo_aprovacao ?? $trabalho->id]) }}" target="_blank" class="btn btn-success">
                                    Baixar Carta de Aceite (PDF)
                                </a>
                            @endif
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

@endsection

@section('javascript')
    @if (old('trabalhoEditId'))
        <script>
            $(document).ready(function() {
                $('#modalEditarTrabalho_{{ old('
                                        trabalhoEditId ') }}').modal('show');
            })
        </script>
    @endif

    @if (isset($trabalho))
        <script>
            function montarLinhaInputCorrecao(id, event) {
                event.preventDefault();
                var coautores = document.getElementById("coautoresCorrecao_" + id);
                var html = `
                    <div class="item card mt-2">
                        <div class="row card-body p-2 align-items-center">
                            <div class="col-sm-5">
                                <label class="small mb-1">E-mail</label>
                                <input type="email" class="form-control form-control-sm emailCoautor" 
                                    name="emailCoautor_${id}[]" placeholder="E-mail" oninput="buscarEmail(this)" required>
                            </div>
                            <div class="col-sm-5">
                                <label class="small mb-1">Nome Completo</label>
                                <input type="text" class="form-control form-control-sm" 
                                    name="nomeCoautor_${id}[]" placeholder="Nome completo" required>
                            </div>
                            <div class="col-sm-2 d-flex align-items-center justify-content-around mt-3">
                                <a href="#" style="color: #d30909;" onclick="deletarCoautor(this, event)" title="Remover">
                                    <img src="{{ asset('img/icons/trash-alt-regular.svg') }}" width="18">
                                </a>
                                <a href="#" onclick="moverCoautor(this, 1, event)" title="Subir">
                                    <img src="{{ asset('img/icons/sobe.png') }}" width="18">
                                </a>
                                <a href="#" onclick="moverCoautor(this, 0, event)" title="Descer">
                                    <img src="{{ asset('img/icons/desce.png') }}" width="18">
                                </a>
                            </div>
                        </div>
                    </div>
                `;
                $(coautores).append(html);
            }

            function deletarCoautor(btn, event) {
                event.preventDefault();
                var card = btn.closest('.item');
                if (card) {
                    card.remove();
                }
            }

            $(document).ready(function() {
                $('.char-count').keyup(function() {

                    var maxLength = parseInt($(this).attr('maxlength'));
                    var length = $(this).val().length;
                    // var newLength = maxLength-length;

                    var name = $(this).attr("name");
                    $('#' + name).text(length);
                });
            });

            $(document).ready(function() {
                $('.palavra').keyup(function() {
                    var myText = this.value.trim();
                    var wordsArray = myText.split(/\s+/g);
                    var words = wordsArray.length;
                    var min = parseInt(($('#minpalavras').text()));
                    var max = parseInt(($('#maxpalavras').text()));
                    if (words < min || words > max) {
                        this.setCustomValidity('Número de palavras não permitido. Você possui atualmente ' +
                            words + ' palavras.');
                    } else {
                        this.setCustomValidity('');
                    }
                    var name = $(this).attr("name");
                    $('#' + name).text(words);
                });
            });


            function moverCoautor(btn, direcao, event) {
                event.preventDefault();
                var card = btn.closest('.item');
                var container = card.parentElement;

                if (direcao === 1) { // SUBIR
                    var anterior = card.previousElementSibling;
                    // Impede de subir antes do primeiro elemento (que é o Autor Principal)
                    if (anterior && anterior !== container.firstElementChild) {
                        container.insertBefore(card, anterior);
                    }
                } else if (direcao === 0) { // DESCER
                    var proximo = card.nextElementSibling;
                    if (proximo) {
                        container.insertBefore(proximo, card);
                    }
                }
            }

            function buscarEmail(input) {
                var emailBuscado = input.value.trim();
                var card = input.closest('.row');
                var inputName = card.querySelector('input[type="text"]');

                if (emailBuscado && emailBuscado.indexOf('@') !== -1 && emailBuscado.indexOf('.') !== -1) {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route('search.user') }}',
                        data: { email: emailBuscado },
                        dataType: 'json',
                        success: function(res) {
                            if (res.user && res.user[0] != null) {
                                inputName.value = res.user[0]['name'];
                            }
                        }
                    });
                }
            }
        </script>
    @endif
@endsection