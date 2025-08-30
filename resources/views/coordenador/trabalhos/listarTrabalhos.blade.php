@extends('coordenador.detalhesEvento')

@section('menu')
    <div id="divListarTrabalhos" style="display: block">

        <div class="row ">
            <div class="col-sm-6">
                <h1 class="">Trabalhos</h1>
            </div>

            <div class="col-sm-3"></div>
            <div class="col-sm-3">
                <div class="row mt-1">
                    <a class="btn btn-primary col-sm" href="{{ route('evento.downloadResumos', $evento) }}">Baixar resumos</a>
                </div>
                <div class="row mt-1">
                    <a class="btn btn-primary col-sm" href="{{ route('evento.downloadTrabalhos', $evento) }}">Exportar trabalhos .csv</a>
                </div>
                <div class="row mt-1">
                    <a class="btn btn-primary col-sm" href="{{ route('evento.downloadTrabalhosAprovadosPDF', $evento) }}">
                        Lista de Trabalhos Aprovados (PDF)
                    </a>
                </div>
            </div>
        </div>

        {{-- Filtro de Status --}}
        <div class="btn-group mb-2" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Opções de Filtro: {{ ucfirst(str_replace('_', ' ', $status)) }}
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item" href="{{ route('coord.listarTrabalhos', ['eventoId' => $evento->id, 'column' => 'titulo', 'direction' => 'asc', 'status' => 'rascunho']) }}">Todos</a>
                    <a class="dropdown-item" href="{{ route('coord.listarTrabalhos', ['eventoId' => $evento->id, 'column' => 'titulo', 'direction' => 'asc', 'status' => 'arquivado']) }}">Arquivados</a>
                    <a class="dropdown-item" href="{{ route('coord.listarTrabalhos', ['eventoId' => $evento->id, 'column' => 'titulo', 'direction' => 'asc', 'status' => 'no_revisor']) }}">Sem avaliador</a>
                    <a class="dropdown-item" href="{{ route('coord.listarTrabalhos', ['eventoId' => $evento->id, 'column' => 'titulo', 'direction' => 'asc', 'status' => 'with_revisor']) }}">Com avaliador</a>
                </div>
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
                <form method="GET" action="{{ route('coord.listarTrabalhos') }}">
                    <input type="hidden" name="eventoId" value="{{ $evento->id }}">
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
                                <a href="{{ route('coord.listarTrabalhos', ['eventoId' => $evento->id, 'column' => request('column', 'titulo'), 'direction' => request('direction', 'asc'), 'status' => request('status', 'rascunho')]) }}" class="btn btn-outline-success btn-sm">Limpar filtros</a>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        @foreach ($modalidades as $modalidade)
            @if ($modalidade->trabalhos_count > 0)
                <div class="row justify-content-center">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Modalidade: <span class="card-subtitle mb-2 text-muted">{{ $modalidade->nome }} ({{ $modalidade->trabalhos_count }})</span></h5>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Título</th>
                                                <th>Área</th>
                                                <th>Autor</th>
                                                @foreach ($modalidade->midiasExtra as $midia)
                                                    <th scope="col">{{$midia->nome}}</th>
                                                @endforeach
                                                @if ($modalidade->apresentacao)
                                                    <th scope="col">Apresentação</th>
                                                @endif
                                                <th>Avaliadores</th>
                                                <th>Avaliações</th>
                                                <th>Data</th>
                                                <th>Atribuir</th>
                                                <th>Arquivar</th>
                                                <th>Excluir</th>
                                                <th>Editar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($modalidade->trabalho as $trabalho)
                                                <tr id="trab{{ $trabalho->id }}">
                                                    <td>{{ $trabalho->id }}</td>
                                                    <td>
                                                        @if ($trabalho->tem_arquivo)
                                                            <a href="{{ route('downloadTrabalho', ['id' => $trabalho->id]) }}">{{ $trabalho->titulo }}</a>
                                                        @else
                                                            {{ $trabalho->titulo }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $trabalho->area->nome }}</td>
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
                                                    @if ($modalidade->apresentacao)
                                                        <td>{{ $trabalho->tipo_apresentacao }}</td>
                                                    @endif
                                                    <td>{{ $trabalho->atribuicoes_count }}</td>
                                                    <td>{{ $trabalho->quantidade_avaliacoes }}</td>
                                                    <td>{{ $trabalho->created_at?->format('d/m/Y H:i') }}</td>
                                                    <td style="text-align:center">
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalTrabalho{{ $trabalho->id }}">
                                                            <img src="{{ asset('img/icons/documento.svg') }}" class="icon-card" width="20" alt="atribuir">
                                                        </a>
                                                    </td>
                                                        <td style="text-align:center">
                                                            @if ($trabalho->status == 'arquivado')
                                                                <a href="{{ route('trabalho.status', [$trabalho->id, 'rascunho']) }}" title="Desarquivar"><img src="{{ asset('img/icons/archive.png') }}" width="20" alt="Desarquivar"></a>
                                                            @else
                                                                <a href="{{ route('trabalho.status', [$trabalho->id, 'arquivado']) }}" title="Arquivar"><img src="{{ asset('img/icons/archive.png') }}" width="20" alt="Arquivar"></a>
                                                            @endif
                                                        </td>
                                                        <td style="text-align:center">
                                                            @if ($trabalho->status == 'arquivado')
                                                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalExcluirTrabalho_{{ $trabalho->id }}"><img src="{{ asset('img/icons/lixo.png') }}" width="20" alt="Excluir"></a>
                                                            @endif
                                                        </td>
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

        @foreach ($trabalhos as $trabalho)
            <x-modal-adicionar-revisor :trabalho="$trabalho" :evento="$evento" />
            <x-modal-excluir-trabalho :trabalho="$trabalho" />
        @endforeach

        @if($trabalhos->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $trabalhos->links() }}
            </div>
        @endif

        @include('coordenador.trabalhos.export_certifica_modal', compact('evento'))
    </div>
@endsection

@section('javascript')
    @parent
    <script>
        const id = {!! json_encode(old('trabalhoId')) !!};
        $(document).ready(function(){
            if(id != null){
                $('#modalTrabalho'+id).modal('show');
            }
        });
    </script>
@endsection
