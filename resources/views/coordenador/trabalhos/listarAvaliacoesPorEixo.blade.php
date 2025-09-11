@extends('coordenador.detalhesEvento')

@section('menu')
    <div id="divListarAvaliacoesPorEixo">

        <div class="row">
            <div class="col-sm-12">
                <h1 class="">Avaliações por Eixo Temático</h1>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Filtrar por Eixo</h5>
                <form method="GET" action="{{ route('coord.listarAvaliacoesPorEixo') }}">
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

        @if($eixoSelecionado)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-12 text-end">
                            <a href="{{ route('evento.exportarAvaliadoresEixos', ['evento' => $evento->id, 'eixo' => $eixoSelecionado]) }}"
                               class="btn btn-outline-primary">
                                Exportar Avaliadores do Eixo (XLSX)
                            </a>
                        </div>
                    </div>
                    <form method="GET" action="{{ route('coord.listarAvaliacoesPorEixo') }}">
                        <input type="hidden" name="eventoId" value="{{ $evento->id }}">
                        <input type="hidden" name="eixo_id" value="{{ $eixoSelecionado }}">
                        <input type="hidden" name="column" value="{{ request('column', 'titulo') }}">
                        <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="id" class="form-label">Buscar por ID</label>
                                <input type="number" class="form-control" name="id" value="{{ request('id') }}" placeholder="Digite o ID...">
                            </div>
                            <div class="col-md-8">
                                <label for="search" class="form-label">Buscar por Título</label>
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Digite o título do trabalho...">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button class="btn btn-primary w-100" type="submit">Buscar</button>
                            </div>
                        </div>
                        @if(request('search') || request('id'))
                            <div class="row mt-2">
                                <div class="col-12">
                                    <a href="{{ route('coord.listarAvaliacoesPorEixo', ['eventoId' => $evento->id, 'eixo_id' => $eixoSelecionado, 'status' => $status]) }}" class="btn btn-outline-success btn-sm">Limpar filtros</a>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <div class="btn-group mb-2" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Opções de Filtro: {{ $status == 'rascunho' ? 'Todos' : 'Arquivados' }}
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item" href="{{ route('coord.listarAvaliacoesPorEixo', ['eventoId' => $evento->id, 'eixo_id' => $eixoSelecionado, 'status' => 'rascunho']) }}">Todos</a>
                        <a class="dropdown-item" href="{{ route('coord.listarAvaliacoesPorEixo', ['eventoId' => $evento->id, 'eixo_id' => $eixoSelecionado, 'status' => 'arquivado']) }}">Arquivados</a>
                    </div>
                </div>
            </div>

            @foreach ($trabalhosPorModalidade as $modalidade)
                <div class="row justify-content-center" style="width: 100%;">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Modalidade: <span class="card-subtitle mb-2 text-muted">{{ $modalidade->nome }}</span></h5>
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">ID</th>
                                                <th scope="col">Trabalho</th>
                                                <th scope="col">Autor</th>
                                                <th scope="col">Avaliador(es)</th>
                                                <th scope="col" class="text-center">Data da atribuição</th>
                                                <th scope="col">Status</th>
                                                <th scope="col" style="text-align:center">Parecer</th>
                                                <th scope="col" class="text-center">Encaminhado para o autor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($modalidade->trabalhos_da_modalidade as $trabalho)
                                                <tr>
                                                    <td>{{ $trabalho->id }}</td>
                                                    <td>
                                                        @if ($trabalho->arquivo && count($trabalho->arquivo) > 0)
                                                            <a href="{{ route('downloadTrabalho', ['id' => $trabalho->id]) }}">{{ $trabalho->titulo }}</a>
                                                        @else
                                                            {{ $trabalho->titulo }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $trabalho->autor->name }}</td>
                                                    {{-- <td>{{$trabalho->area->nome}}</td> --}}
                                                    <td>
                                                        @foreach ($trabalho->atribuicoes as $revisor)
                                                            {{ $revisor->user->name }}<br>
                                                        @endforeach
                                                    </td>
                                                    <td class="text-center">
                                                        @foreach ($trabalho->atribuicoes as $revisor)
                                                            {{ optional($revisor->pivot->created_at)->format('d/m/Y H:i') }}<br>
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @forelse ($trabalho->atribuicoes as $revisor)
                                                            @if($trabalho->avaliado($revisor->user))
                                                                Avaliado
                                                            @else
                                                                Processando
                                                            @endif
                                                            <br>
                                                        @empty
                                                            Sem avaliador
                                                        @endforelse
                                                    </td>
                                                    <td style="text-align:center">
                                                        @foreach ($trabalho->atribuicoes as $revisor)
                                                            @if($trabalho->avaliado($revisor->user))
                                                                <a href="{{ route('coord.visualizarRespostaFormulario', ['eventoId' => $evento->id, 'modalidadeId' => $trabalho->modalidadeId, 'trabalhoId' => $trabalho->id, 'revisorId' => $revisor->id]) }}">
                                                                    <img src="{{ asset('img/icons/eye-regular.svg') }}" style="width:20px">
                                                                </a><br>
                                                            @else
                                                                - <br>
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td class="text-center">
                                                        @foreach($trabalho->atribuicoes as $revisor)
                                                            @if($trabalho->avaliado($revisor->user))
                                                                @if ($trabalho->getParecerAtribuicao($revisor->user) != "encaminhado")
                                                                    Não
                                                                @else
                                                                    Sim
                                                                @endif
                                                            @else
                                                                Processando
                                                            @endif
                                                            <br>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center">Nenhum trabalho encontrado nesta modalidade para o eixo selecionado.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if($trabalhosPaginados->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $trabalhosPaginados->links() }}
                </div>
            @endif

        @elseif(request()->has('eixo_id'))
             <div class="alert alert-info" role="alert">Nenhum trabalho encontrado para este eixo.</div>
        @else
            <div class="alert alert-info" role="alert">Selecione um eixo para visualizar as avaliações.</div>
        @endif
    </div>
@endsection
