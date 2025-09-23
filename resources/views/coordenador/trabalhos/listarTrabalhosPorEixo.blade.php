@extends('coordenador.detalhesEvento')

@section('menu')
    <div id="divListarTrabalhos" style="display: block">

        <div class="row ">
            <div class="col-sm-6"><h1 class="">Trabalhos por Eixo</h1></div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Filtrar por Eixo</h5>
                <form method="GET" action="{{ route('coord.listarTrabalhosPorEixo') }}">
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
                <form method="GET" action="{{ route('coord.listarTrabalhosPorEixo') }}">
                    <input type="hidden" name="eventoId" value="{{ $evento->id }}">
                    <input type="hidden" name="eixo_id" value="{{ $eixoSelecionado }}">
                    <input type="hidden" name="status" value="{{ $status }}">
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
                                <a href="{{ route('coord.listarTrabalhosPorEixo', ['eventoId' => $evento->id, 'column' => request('column', 'titulo'), 'direction' => request('direction', 'asc'), 'status' => request('status', 'rascunho')]) }}" class="btn btn-outline-success btn-sm">Limpar filtros</a>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        @if($eixoSelecionado)
            {{-- Filtro de Status --}}
            <div class="btn-group mb-2" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Opções de Filtro: {{ ucfirst(str_replace('_', ' ', $status)) }}
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item" href="{{ route('coord.listarTrabalhosPorEixo', ['column' => 'titulo', 'direction' => 'asc', 'status' => 'rascunho']) }}?eixo_id={{ $eixoSelecionado }}&eventoId={{ $evento->id }}">Todos</a>
                    <a class="dropdown-item" href="{{ route('coord.listarTrabalhosPorEixo', ['column' => 'titulo', 'direction' => 'asc', 'status' => 'arquivado']) }}?eixo_id={{ $eixoSelecionado }}&eventoId={{ $evento->id }}">Arquivados</a>
                    <a class="dropdown-item" href="{{ route('coord.listarTrabalhosPorEixo', ['column' => 'titulo', 'direction' => 'asc', 'status' => 'no_revisor']) }}?eixo_id={{ $eixoSelecionado }}&eventoId={{ $evento->id }}">Sem avaliador</a>
                    <a class="dropdown-item" href="{{ route('coord.listarTrabalhosPorEixo', ['column' => 'titulo', 'direction' => 'asc', 'status' => 'with_revisor']) }}?eixo_id={{ $eixoSelecionado }}&eventoId={{ $evento->id }}">Com avaliador</a>
                </div>
            </div>

            @foreach ($modalidades as $modalidade)
                @if ($modalidade->trabalhos_count > 0)
                    <div class="row justify-content-center">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Modalidade: <span class="card-subtitle mb-2 text-muted">{{$modalidade->nome}} ({{ $modalidade->trabalhos_count }})</span></h5>
                                    <div class="table-responsive">
                                        {{-- A tabela aqui é idêntica à de listarTrabalhos.blade.php, usando as mesmas variáveis --}}
                                        <table class="table table-sm table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Título</th>
                                                    <th>Autor</th>
                                                    @foreach ($modalidade->midiasExtra as $midia)
                                                        <th scope="col">{{$midia->nome}}</th>
                                                    @endforeach
                                                    <th>Avaliadores</th>
                                                    <th>Avaliações</th>
                                                    <th>Data</th>
                                                    <th>Atribuir</th>
                                                    @can('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento)
                                                        <th>Arquivar</th>
                                                        @if ($status == 'rascunho')
                                                            <th style="display: none;">Excluir</th>
                                                        @else
                                                            <th>Excluir</th>
                                                        @endif
                                                    @endcan
                                                    <th>Editar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($modalidade->trabalho as $trabalho)
                                                    <tr>
                                                        <td>{{ $trabalho->id }}</td>
                                                        <td>
                                                            @if ($trabalho->tem_arquivo)
                                                                <a href="{{route('downloadTrabalho', ['id' => $trabalho->id])}}">{{$trabalho->titulo}}</a>
                                                            @else
                                                                {{$trabalho->titulo}}
                                                            @endif
                                                        </td>
                                                        <td>{{ $trabalho->autor->name }}</td>
                                                        @foreach ($modalidade->midiasExtra as $midia)
                                                            <td>
                                                                @if($trabalho->midias_extra_verificadas->has($midia->id))
                                                                    <a href="{{route('downloadMidiaExtra', ['id' => $trabalho->id, 'id_midia' => $midia->id])}}">
                                                                        <img src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px" alt="Baixar mídia extra">
                                                                    </a>
                                                                @endif
                                                            </td>
                                                        @endforeach
                                                        <td>{{ $trabalho->atribuicoes_count }}</td>
                                                        <td>{{ $trabalho->quantidade_avaliacoes }}</td>
                                                        <td>{{ $trabalho->created_at?->format('d/m/Y H:i') }}</td>
                                                        <td style="text-align:center">
                                                            <livewire:buttons.ver-trabalho-btn
                                                                :trabalho-id="$trabalho->id"
                                                                :evento-id="$evento->id"
                                                            />
                                                        </td>
                                                        @can('isCoordenadorOrCoordenadorDaComissaoCientifica', $trabalho->evento)
                                                            <td style="text-align:center">
                                                                @if ($trabalho->status == 'arquivado')
                                                                    <a href="{{ route('trabalho.status', [$trabalho->id, 'rascunho']) }}"><img src="{{ asset('img/icons/archive.png') }}" width="20" alt="Desarquivar"></a>
                                                                @else
                                                                    <a href="{{ route('trabalho.status', [$trabalho->id, 'arquivado'] ) }}"><img src="{{ asset('img/icons/archive.png') }}" width="20" alt="Arquivar"></a>
                                                                @endif
                                                            </td>
                                                            @if ($trabalho->status == 'arquivado')
                                                                <td style="text-align:center">
                                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalExcluirTrabalho_{{$trabalho->id}}"><img src="{{ asset('img/icons/lixo.png') }}" width="20" alt="Excluir"></a>
                                                                </td>
                                                            @endif
                                                        @endcan
                                                        <td style="text-align:center">
                                                            <a href="{{ route('coord.trabalho.edit', ['id' => $trabalho->id]) }}"><img src="{{ asset('img/icons/edit-regular.svg') }}" width="20" alt="Editar"></a>
                                                        </td>
                                                    </tr>
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

            @if($trabalhos && $trabalhos->count() == 0)
                 <div class="alert alert-info" role="alert">Nenhum trabalho encontrado para este eixo com os filtros aplicados.</div>
            @endif

        @else
            <div class="alert alert-info" role="alert">Selecione um eixo para visualizar os trabalhos.</div>
        @endif

        @if($trabalhos && $trabalhos->isNotEmpty())
            @foreach ($trabalhos as $trabalho)
                <x-modal-excluir-trabalho :trabalho="$trabalho" />
            @endforeach

            @if(method_exists($trabalhos, 'hasPages') && $trabalhos->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $trabalhos->links() }}
                </div>
            @endif
        @endif

    </div>
@endsection

@section('javascript')
    @parent

@endsection
