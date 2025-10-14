@extends('coordenador.detalhesEvento')
@section('menu')
    <div id="divListarAvaliacoes" style="display: block">
        <div class="row">
            <div class="col-12">
                <h1 class="">Avaliações</h1>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('coord.listarAvaliacoes', ['eventoId' => $evento->id]) }}">
                            <input type="hidden" name="eventoId" value="{{ $evento->id }}">
                            <input type="hidden" name="column" value="{{ request('column', 'titulo') }}">
                            <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}">
                            <input type="hidden" name="status" value="{{ request('status', 'rascunho') }}">
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
                                        <a href="{{ route('coord.listarAvaliacoes', ['eventoId' => $evento->id, 'column' => request('column', 'titulo'), 'direction' => request('direction', 'asc'), 'status' => request('status', 'rascunho')]) }}" class="btn btn-outline-success btn-sm">Limpar filtros</a>
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-group mb-2" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    Opções
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item"
                        href="{{ route('coord.listarAvaliacoes', ['eventoId' => $evento->id, 'titulo', 'asc', 'rascunho']) }}">
                        Todos
                    </a>
                    <a class="dropdown-item"
                        href="{{ route('coord.listarAvaliacoes', ['eventoId' => $evento->id, 'titulo', 'asc', 'arquivado']) }}">
                        Arquivados
                    </a>
                    <a class="dropdown-item disabled" href="#">
                        Submetidos
                    </a>
                    <a class="dropdown-item disabled" href="#">
                        Aprovados
                    </a>
                    <a class="dropdown-item disabled" href="#">
                        Corrigidos
                    </a>
                    <a class="dropdown-item disabled" href="#">
                        Rascunhos
                    </a>
                </div>
            </div>
        </div>
        @foreach ($trabalhosPorModalidade as $trabalhos)
            <div class="row justify-content-center" style="width: 100%;">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            @if (!is_null($trabalhos->first()))
                                <h5 class="card-title">Modalidade: <span
                                        class="card-subtitle mb-2 text-muted">{{ $trabalhos->first()->modalidade->nome }}</span></h5>
                            @endif
                            <div class="row table-trabalhos">
                                <div class="col-sm-12">
                                    <br/>
                                    <table class="table table-hover table-responsive-lg table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">ID</th>
                                                <th scope="col">
                                                    Trabalho
                                                    <a
                                                        href="{{ route('coord.listarAvaliacoes', ['eventoId' => $evento->id, 'titulo', 'asc']) }}">
                                                        <img src="{{ asset('img/icons/sobe.png') }}" alt="Ordenar crescente" style="width: 16px; height: 16px;">
                                                    </a>
                                                    <a
                                                        href="{{ route('coord.listarAvaliacoes', ['eventoId' => $evento->id, 'titulo', 'desc']) }}">
                                                        <img src="{{ asset('img/icons/desce.png') }}" alt="Ordenar decrescente" style="width: 16px; height: 16px;">
                                                    </a>
                                                </th>
                                                <th scope="col">
                                                    Autor
                                                    <a
                                                        href="{{ route('coord.listarAvaliacoes', ['eventoId' => $evento->id, 'autor', 'asc']) }}">
                                                        <img src="{{ asset('img/icons/sobe.png') }}" alt="Ordenar crescente" style="width: 16px; height: 16px;">
                                                    </a>
                                                    <a
                                                        href="{{ route('coord.listarAvaliacoes', ['eventoId' => $evento->id, 'autor', 'desc']) }}">
                                                        <img src="{{ asset('img/icons/desce.png') }}" alt="Ordenar decrescente" style="width: 16px; height: 16px;">
                                                    </a>
                                                </th>
                                                <th scope="col">
                                                    Área
                                                    <a
                                                        href="{{ route('coord.listarAvaliacoes', ['eventoId' => $evento->id, 'area', 'asc']) }}">
                                                        <img src="{{ asset('img/icons/sobe.png') }}" alt="Ordenar crescente" style="width: 16px; height: 16px;">
                                                    </a>
                                                    <a
                                                        href="{{ route('coord.listarAvaliacoes', ['eventoId' => $evento->id, 'area', 'desc']) }}">
                                                        <img src="{{ asset('img/icons/desce.png') }}" alt="Ordenar decrescente" style="width: 16px; height: 16px;">
                                                    </a>
                                                </th>
                                                <th scope="col">Avaliador(es)</th>
                                                <th scope="col" class="text-center">
                                                    Data da atribuição
                                                    <a href="{{ route('coord.listarAvaliacoes', ['eventoId' => $evento->id, 'data_atribuicao', 'asc']) }}"></a>
                                                    <a href="{{ route('coord.listarAvaliacoes', ['eventoId' => $evento->id, 'data_atribuicao', 'desc']) }}"></a>
                                                </th>
                                                <th scope="col">Status</th>
                                                <th scope="col" style="text-align:center">Parecer</th>
                                                <th scope="col" class="text-center">Encaminhado para o autor</th>
                                                {{-- <th scope="col" class="text-center">Lembrete de correção enviado</th> --}}
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($trabalhos as $trabalho)
                                                <tr>
                                                <td>{{ $trabalho->id }}</td>
                                                <td>
                                                    @if ($trabalho->arquivo && count($trabalho->arquivo) > 0)
                                                        <a
                                                            href="{{ route('downloadTrabalho', ['id' => $trabalho->id]) }}">
                                                            <span class="d-inline-block"
                                                                class="d-inline-block" tabindex="0" data-bs-toggle="tooltip"
                                                                title="{{ $trabalho->titulo }}">
                                                                {{ $trabalho->titulo }}
                                                            </span>
                                                        </a>
                                                    @else
                                                        <span class="d-inline-block" class="d-inline-block"
                                                            tabindex="0" data-bs-toggle="tooltip"
                                                            title="{{ $trabalho->titulo }}">
                                                            {{ $trabalho->titulo }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>{{ $trabalho->autor->name }}</td>
                                                <td> {{$trabalho->area->nome}} </td>
                                                <td>
                                                    @foreach ($trabalho->atribuicoes as $revisor)
                                                        {{ $revisor->user->name }}
                                                        <br>
                                                    @endforeach
                                                </td>
                                                <td class="text-center">
                                                    @foreach ($trabalho->atribuicoes as $revisor)
                                                        {{ \Carbon\Carbon::parse($revisor->pivot->created_at)->format('d/m/Y H:i') }}
                                                        <br>
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
                                                            <a
                                                                href="{{ route('coord.visualizarRespostaFormulario', ['eventoId' => $evento->id, 'modalidadeId' => $trabalho->modalidadeId, 'trabalhoId' => $trabalho->id, 'revisorId' => $revisor->id]) }}">
                                                                <img src="{{ asset('img/icons/eye-regular.svg') }}"
                                                                    style="width:20px">
                                                            </a>
                                                            @endif
                                                        <br>
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
                                                {{-- <td class="text-center">{{$trabalho->lembrete_enviado ? 'Sim' : 'Não'}}</td> --}}
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center">Nenhum trabalho encontrado nesta modalidade.</td>
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
        @if(isset($trabalhosPaginados))
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted">
                                Mostrando {{ $trabalhosPaginados->firstItem() ?? 0 }} até {{ $trabalhosPaginados->lastItem() ?? 0 }}
                                de {{ $trabalhosPaginados->total() }} resultados
                            </p>
                        </div>
                        <div>
                            {{ $trabalhosPaginados->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
