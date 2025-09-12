@extends('layouts.app')

@section('sidebar')


@endsection
@section('content')
    <div class="container">

        <div class="row ">
            <div class="col-sm-6">
                <h1 class="">Trabalhos Validados</h1>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('coord.listarValidacoes', $evento->id) }}">
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
                            <button type="submit" id="busca" class="btn btn-primary w-100">Buscar</button>
                        </div>
                    </div>
                    @if(request('titulo') || request('id'))
                        <div class="row mt-2">
                            <div class="col-12">
                                <a href="{{ route('coord.listarValidacoes', ['eventoId' => $evento->id, 'column' => request('column', 'titulo'), 'direction' => request('direction', 'asc'), 'status' => request('status', 'rascunho')]) }}" class="btn btn-outline-success btn-sm">Limpar filtros</a>
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

        @foreach ($modalidades as $modalidade)
            @if(count($modalidade->trabalho) > 0)
                <div class="row justify-content-center" style="width: 100%;">
                    <div class="col-sm-12">
                        <div class="card mb-4">
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
                                                <th><input type="checkbox" onchange="alterarSelecionados(this)"></th>
                                                <th scope="col">ID</th>
                                                <th scope="col">Trabalho inicial</th>
                                                <th scope="col">Trabalho revisado</th>
                                                <th scope="col">Autor</th>
                                                <th scope="col">Parecer</th>
                                                <th scope="col" class="text-center">Validação</th>
                                                <th scope="col" style="text-align:center;">Ações</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($modalidade->trabalho as $trabalho)
                                            <tr>
                                                <td><input type="checkbox" name="trabalhosSelecionados[]" value="{{$trabalho->id}}"></td>
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
                                                <td style="text-align:center">
                                                    @foreach ($trabalho->atribuicoes as $revisor)
                                                        <a href="{{route('coord.visualizarRespostaFormulario', ['eventoId' => $evento->id, 'modalidadeId' => $trabalho->modalidadeId, 'trabalhoId' => $trabalho->id, 'revisorId' => $revisor->id])}}">
                                                            <img src="{{asset('img/icons/eye-regular.svg')}}" style="width:20px">
                                                        </a>
                                                        <br>
                                                    @endforeach
                                                </td>
                                                <td class="text-center">
                                                    @switch($trabalho->avaliado)
                                                        @case('corrigido')
                                                            <span class="badge bg-success">Aprovado</span>
                                                            @break
                                                        @case('corrigido_parcialmente')
                                                            <span class="badge bg-warning text-dark">Aprovado Parcialmente</span>
                                                            @break
                                                        @case('nao_corrigido')
                                                            <span class="badge bg-danger">Reprovado</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-secondary">Em Análise</span>
                                                    @endswitch

                                                    @if(in_array($trabalho->avaliado, ['corrigido', 'corrigido_parcialmente', 'nao_corrigido']))
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
                                                            @elseif(($trabalho->aprovado === false && auth()->user()->can('isCoordenadorOrCoordenadorDaComissaoCientifica', $trabalho->evento)) || (is_null($trabalho->aprovado)))
                                                            <button class="btn btn-success btn-sm" name="btn-avaliacao-aprovar-{{$trabalho->id}}"
                                                                data-bs-toggle="modal" data-bs-target="#avaliacao-aprovar-{{$trabalho->id}}">
                                                                Aprovar Trabalho
                                                            </button>
                                                        @endif

                                                        @if($trabalho->aprovado === false)
                                                            <button class="btn btn-danger btn-sm" disabled>
                                                                Trabalho Reprovado
                                                            </button>
                                                        @elseif(($trabalho->aprovado === true && auth()->user()->can('isCoordenadorOrCoordenadorDaComissaoCientifica', $trabalho->evento)) || (is_null($trabalho->aprovado)))
                                                            <button class="btn btn-danger btn-sm" name="btn-avaliacao-reprovar-{{$trabalho->id}}"
                                                                data-bs-toggle="modal" data-bs-target="#avaliacao-reprovar-{{$trabalho->id}}">
                                                                Reprovar Trabalho
                                                            </button>
                                                        @endif

                                                        @if($trabalho->aprovado === null || auth()->user()->can('isCoordenadorOrCoordenadorDaComissaoCientifica', $trabalho->evento))
                                                            @push('modais')
                                                                @include('coordenador.trabalhos.avaliacao-modal', ['trabalho' => $trabalho, 'valor' => 'true', 'descricao' => 'aprovar'])
                                                                @include('coordenador.trabalhos.avaliacao-modal', ['trabalho' => $trabalho, 'valor' => 'false', 'descricao' => 'reprovar'])
                                                            @endpush
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @push('modais')
                                                @if(in_array($trabalho->avaliado, ['corrigido', 'corrigido_parcialmente', 'nao_corrigido']))
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
            @endif
        @endforeach

        @if($trabalhos->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $trabalhos->links() }}
            </div>
        @endif
    </div>
    @stack('modais')

    @endsection

@section('script')
<script>
    function alterarSelecionados(source) {
        let checkboxes = document.querySelectorAll('input[name="trabalhosSelecionados[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = source.checked);
    }
</script>
@endsection
