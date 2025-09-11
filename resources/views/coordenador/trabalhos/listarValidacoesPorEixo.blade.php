@extends('coordenador.detalhesEvento')

@section('menu')
    <div id="divListarValidacoesPorEixo" style="display: block">

        <div class="row ">
            <div class="col-sm-6"><h1 class="">Validações por Eixo</h1></div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Filtrar por Eixo</h5>
                <form method="GET" action="{{ route('coord.listarValidacoesPorEixo') }}">
                    <input type="hidden" name="eventoId" value="{{ $evento->id }}">
                    <div class="row">
                        <div class="col-md-8">
                            <label for="eixo_id" class="form-label">Selecione o eixo:</label>
                            <select class="form-control" id="eixo_id" name="eixo_id">
                                <option value="">-- Selecione um eixo --</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}" @if($eixoSelecionado == $area->id) selected @endif>{{ $area->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('coord.listarValidacoesPorEixo') }}">
                    <input type="hidden" name="eventoId" value="{{ $evento->id }}">
                    <input type="hidden" name="eixo_id" value="{{ $eixoSelecionado }}">
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
                                <a href="{{ route('coord.listarValidacoesPorEixo', ['eventoId' => $evento->id, 'eixo_id' => $eixoSelecionado]) }}" class="btn btn-outline-success btn-sm">Limpar filtros</a>
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
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-2">
                            <button class="btn btn-success btn-sm me-2" disabled>Aprovar</button>
                            <small class="text-muted">Trabalho pode ser aprovado (inscrição paga por autor e/ou coautor)</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-2">
                            <button class="btn btn-danger btn-sm me-2" disabled>Reprovar</button>
                            <small class="text-muted">Trabalho pode ser reprovado</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-2">
                            <button class="btn btn-warning btn-sm me-2" disabled>Aprovar</button>
                            <small class="text-muted">Não pode aprovar (nenhum autor/coautor pagou a inscrição)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($eixoSelecionado)
            @if($trabalhosPorModalidade->isNotEmpty())
                @foreach ($trabalhosPorModalidade as $modalidade)
                    <div class="row justify-content-center">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Modalidade: <span class="card-subtitle mb-2 text-muted">{{ $modalidade->nome }} ({{ $modalidade->trabalhos_da_modalidade->count() }})</span></h5>
                                    @if ($modalidade->inicioValidacao && $modalidade->fimValidacao)
                                        <h5 class="card-title">Validação: <span class="card-subtitle mb-2 text-muted" >{{date("d/m/Y H:i", strtotime($modalidade->inicioValidacao))}} - {{date("d/m/Y H:i",strtotime($modalidade->fimValidacao))}}</span></h5>
                                    @else
                                        <h5 class="card-title">Validação: <span class="card-subtitle mb-2 text-muted" >não haverá</span></h5>
                                    @endif
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Trabalho</th>
                                                    <th>Autor</th>
                                                    <th class="text-center">Validado</th>
                                                    <th style="text-align:center;">Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($modalidade->trabalhos_da_modalidade as $trabalho)
                                                    <tr>
                                                        <td>{{ $trabalho->id }}</td>
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
                                                        <td>{{$trabalho->autor->name}}</td>
                                                        <td class="text-center">
                                                            @if($trabalho->aprovado === true)
                                                                <span class="badge bg-success">Aprovado</span>
                                                            @elseif($trabalho->aprovado === false)
                                                                <span class="badge bg-danger">Reprovado</span>
                                                            @else
                                                                <span class="badge bg-secondary">Em Análise</span>
                                                            @endif

                                                            @if(in_array($trabalho->aprovado, [true, false]))
                                                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalValidacaoDetalhes{{$trabalho->id}}">
                                                                    <img src="{{asset('img/icons/eye-regular.svg')}}" style="width:20px; margin-left: 5px;" title="Ver/Editar Validação">
                                                                </a>
                                                            @endif
                                                        </td>
                                                        <td style="text-align:center">
                                                            <div class="d-flex justify-content-center gap-3">
                                                                @if(!$trabalho->tem_pagamento)
                                                                    <button class="btn btn-warning btn-sm" disabled
                                                                        title="Nenhum autor ou coautor possui inscrição paga no evento">
                                                                        <strong>Aprovar Trabalho</strong>
                                                                    </button>
                                                                @elseif($trabalho->aprovado === true)
                                                                    <button class="btn btn-success btn-sm" disabled>
                                                                        Trabalho Aprovado
                                                                    </button>
                                                                @else
                                                                    <button class="btn btn-success btn-sm" name="btn-avaliacao-aprovar-{{$trabalho->id}}"
                                                                        data-bs-toggle="modal" data-bs-target="#avaliacao-aprovar-{{$trabalho->id}}">
                                                                        Aprovar Trabalho
                                                                    </button>
                                                                @endif

                                                                @if($trabalho->aprovado === false)
                                                                    <button class="btn btn-danger btn-sm" disabled>
                                                                        Trabalho Reprovado
                                                                    </button>
                                                                @else
                                                                    <button class="btn btn-danger btn-sm" name="btn-avaliacao-reprovar-{{$trabalho->id}}"
                                                                        data-bs-toggle="modal" data-bs-target="#avaliacao-reprovar-{{$trabalho->id}}">
                                                                        Reprovar Trabalho
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @push('modais')
                                                        @include('coordenador.trabalhos.avaliacao-modal', ['trabalho' => $trabalho, 'valor' => 'true', 'descricao' => 'aprovar'])
                                                        @include('coordenador.trabalhos.avaliacao-modal', ['trabalho' => $trabalho, 'valor' => 'false', 'descricao' => 'reprovar'])
                                                        @if(in_array($trabalho->aprovado, [true, false]))
                                                            @include('coordenador.trabalhos.validacao-detalhes-modal', ['trabalho' => $trabalho])
                                                        @endif
                                                    @endpush
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-info" role="alert">Nenhum trabalho encontrado para este eixo com os filtros aplicados.</div>
            @endif
        @else
            <div class="alert alert-info" role="alert">Selecione um eixo para visualizar as validações.</div>
        @endif

        @if($trabalhosPaginados && $trabalhosPaginados->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $trabalhosPaginados->links() }}
            </div>
        @endif
    </div>
    @stack('modais')
@endsection