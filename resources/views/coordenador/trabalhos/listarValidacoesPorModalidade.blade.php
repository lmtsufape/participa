@extends('coordenador.detalhesEvento')

@section('menu')
    <div id="divListarValidacoesPorModalidade" style="display: block">

        <div class="row ">
            <div class="col-sm-6">
                <h1 class="">Validações da modalidade: {{ $modalidade->nome }}</h1>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('coord.listarValidacoesPorModalidade', ['eventoId' => $evento->id, 'modalidadeId' => $modalidade->id]) }}">
                    <input type="hidden" name="column" value="{{ request('column', 'titulo') }}">
                    <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}">

                    <div class="row">
                        <div class="col-md-2">
                            <label for="id" class="form-label">Buscar por ID</label>
                            <input type="number" class="form-control" name="id" value="{{ request('id') }}" placeholder="Digite o ID...">
                        </div>
                        <div class="col-md-8">
                            <label for="titulo" class="form-label">Buscar por Título</label>
                            <input type="text" class="form-control" name="titulo" value="{{ request('titulo') }}" placeholder="Digite o título do trabalho...">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Buscar</button>
                        </div>
                    </div>
                    @if(request('titulo') || request('id'))
                        <div class="row mt-2">
                            <div class="col-12">
                                <a href="{{ route('coord.listarValidacoesPorModalidade', ['eventoId' => $evento->id, 'modalidadeId' => $modalidade->id]) }}" class="btn btn-outline-success btn-sm">Limpar filtros</a>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h6 class="card-title mb-3 text-center">Legenda dos Botões de Ação</h6>
                <div class="row">
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <button class="btn btn-success btn-sm me-2" disabled>Aprovar</button>
                            <small class="text-muted">Trabalho pode ser aprovado (inscrição paga por autor e/ou coautor)</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <button class="btn btn-danger btn-sm me-2" disabled>Reprovar</button>
                            <small class="text-muted">Trabalho pode ser reprovado</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <button class="btn btn-secondary btn-sm me-2" disabled>Restaurar</button>
                            <small class="text-muted">Trabalho volta ao estado inicial, nem reprovado, nem aprovado.</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <button class="btn btn-warning btn-sm me-2" disabled>Aprovar</button>
                            <small class="text-muted">Não pode aprovar (nenhum autor/coautor pagou a inscrição)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="justify-content-between d-flex">
                            <div>
                                <h5 class="card-title">Modalidade: <span class="card-subtitle mb-2 text-muted" >{{$modalidade->nome}}</span></h5>
                                @if ($modalidade->inicioValidacao && $modalidade->fimValidacao)
                                    <h5 class="card-title">Validação: <span class="card-subtitle mb-2 text-muted" >{{date("d/m/Y H:i", strtotime($modalidade->inicioValidacao))}} - {{date("d/m/Y H:i",strtotime($modalidade->fimValidacao))}}</span></h5>
                                @else
                                    <h5 class="card-title">Validação: <span class="card-subtitle mb-2 text-muted" >não haverá</span></h5>
                                @endif
                            </div>
                        </div>
                        <div class="table-responsive">
                            <input type="hidden" name="eventoId" value="{{$evento->id}}">
                            <br>
                            <table class="table table-hover table-responsive-lg table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Trabalho inicial</th>
                                        <th scope="col">Trabalho revisado</th>
                                        <th scope="col">Autor</th>
                                        <th scope="col">Parecer</th>
                                        <th scope="col" class="text-center">Situação</th>
                                        <th scope="col" class="text-center">Validação</th>
                                        <th scope="col" class="text-center">Decisão final</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($trabalhos as $trabalho)
                                        <tr>
                                            <td> {{ $trabalho->id }}</td>
                                            <td>
                                                @if ($trabalho->arquivo)
                                                    <a href="{{route('downloadTrabalho', ['id' => $trabalho->id])}}">
                                                        <span class="d-inline-block text-truncate" class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="{{$trabalho->titulo}}" style="max-width: 150px;">
                                                            {{$trabalho->titulo}}
                                                        </span>
                                                    </a>
                                                @else
                                                    <span class="d-inline-block text-truncate" class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="{{$trabalho->titulo}}" style="max-width: 150px;">
                                                        {{$trabalho->titulo}}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($trabalho->arquivoCorrecao)
                                                    <a href="{{route('downloadCorrecao', ['id' => $trabalho->id])}}">
                                                        <span class="d-inline-block text-truncate" class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="{{$trabalho->titulo}}" style="max-width: 150px;">
                                                            {{$trabalho->titulo}}
                                                        </span>
                                                    </a>
                                                @else
                                                    <span class="d-inline-block text-truncate" class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Aguardando envio da correção" style="max-width: 150px;">
                                                        Aguardando envio
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{$trabalho->autor->name}}</td>
                                            <td data-col="parecer" class="text-center">
                                                @foreach ($trabalho->atribuicoes as $revisor)
                                                    @if($trabalho->avaliado($revisor->user))
                                                        <a href="{{route('coord.visualizarRespostaFormulario', ['eventoId' => $evento->id, 'modalidadeId' => $trabalho->modalidadeId, 'trabalhoId' => $trabalho->id, 'revisorId' => $revisor->id])}}">
                                                            <img src="{{asset('img/icons/eye-regular.svg')}}" style="width:20px">
                                                        </a>
                                                    @endif
                                                    <br>
                                                @endforeach
                                            </td>
                                            <td data-col="situacao" class="text-center">
                                                @if($trabalho->aprovado === true)
                                                    <span style="
                                                        display:inline-block; padding:.32rem .6rem; border-radius:999px;
                                                        background:#198754; border:1px solid #146C43; color:#FFFFFF;
                                                        font:600 12.5px/1.1 system-ui, -apple-system, 'Segoe UI', Roboto, Arial, sans-serif;
                                                        letter-spacing:.2px; "
                                                    >Aprovado</span>

                                                @elseif($trabalho->aprovado === false)
                                                    <span style="
                                                        display:inline-block; padding:.32rem .6rem; border-radius:999px;
                                                        background:#DC3545; border:1px solid #B02A37; color:#FFFFFF;
                                                        font:600 12.5px/1.1 system-ui, -apple-system, 'Segoe UI', Roboto, Arial, sans-serif;
                                                        letter-spacing:.2px; "
                                                    >Reprovado</span>

                                                @else
                                                    <span style="
                                                        display:inline-block; padding:.32rem .6rem; border-radius:999px;
                                                        background:#F6C445; border:1px solid #E0AE2E; color:#5A3D00;
                                                        font:600 12.5px/1.1 system-ui, -apple-system, 'Segoe UI', Roboto, Arial, sans-serif;
                                                        letter-spacing:.2px; "
                                                    >Em andamento</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @switch($trabalho->avaliado)
                                                    @case('corrigido')
                                                        <span class="badge text-success border border-success bg-transparent">Completamente</span>
                                                        @break
                                                    @case('corrigido_parcialmente')
                                                        <span class="badge text-warning border border-warning bg-transparent">Parcialmente</span>
                                                        @break
                                                    @case('nao_corrigido')
                                                        <span class="badge text-danger border border-danger bg-transparent">Não aprovado</span>
                                                        @break
                                                    @default
                                                        <span class="badge text-info border border-info bg-transparent">Pendente</span>
                                                @endswitch

                                                @if(in_array($trabalho->avaliado, ['corrigido', 'corrigido_parcialmente', 'nao_corrigido']))
                                                    <div>
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalValidacaoDetalhes{{$trabalho->id}}">
                                                            <img src="{{asset('img/icons/eye-regular.svg')}}" style="width:20px; margin-left: 5px;" title="Ver/Editar Validação">
                                                        </a>
                                                    </div>
                                                @endif
                                            </td>
                                            <td style="text-align:center">
                                                <div class="d-flex justify-content-center gap-3">
                                                    @if((is_null($trabalho->aprovado)))
                                                        <button class="btn btn-success btn-sm" name="btn-avaliacao-aprovar-{{$trabalho->id}}"
                                                            data-bs-toggle="modal" data-bs-target="#avaliacao-aprovar-{{$trabalho->id}}">
                                                            Aprovar
                                                        </button>
                                                    @elseif()

                                                    @if(auth()->user()->can('isCoordenadorOrCoordenadorDaComissaoCientifica', $trabalho->evento) && (!is_null($trabalho->aprovado)))
                                                        <button class="btn btn-secondary btn-sm" name="btn-avaliacao-restaurar-{{$trabalho->id}}"
                                                            data-bs-toggle="modal" data-bs-target="#avaliacao-restaurar-{{$trabalho->id}}">
                                                            Restaurar
                                                        </button>
                                                    @endif

                                                    @if((is_null($trabalho->aprovado)))
                                                        <button class="btn btn-danger btn-sm" name="btn-avaliacao-reprovar-{{$trabalho->id}}"
                                                            data-bs-toggle="modal" data-bs-target="#avaliacao-reprovar-{{$trabalho->id}}">
                                                            Reprovar
                                                        </button>
                                                    @endif

                                                    @if($trabalho->aprovado === null || auth()->user()->can('isCoordenadorOrCoordenadorDaComissaoCientifica', $trabalho->evento))
                                                        @push('modais')
                                                            @include('coordenador.trabalhos.avaliacao-modal', ['trabalho' => $trabalho, 'valor' => 'true', 'descricao' => 'aprovar'])
                                                            @include('coordenador.trabalhos.avaliacao-modal', ['trabalho' => $trabalho, 'valor' => 'false', 'descricao' => 'reprovar'])
                                                            @include('coordenador.trabalhos.avaliacao-modal', ['trabalho' => $trabalho, 'valor' => 'restaurar', 'descricao' => 'restaurar'])
                                                            @if(in_array($trabalho->avaliado, ['corrigido', 'corrigido_parcialmente', 'nao_corrigido']))
                                                                @include('coordenador.trabalhos.validacao-detalhes-modal', ['trabalho' => $trabalho])
                                                            @endif
                                                        @endpush
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">Nenhuma validação encontrada para esta modalidade.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($trabalhos->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $trabalhos->links() }}
            </div>
        @endif
    </div>
    @stack('modais')
@endsection