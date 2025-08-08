@extends('coordenador.detalhesEvento')

@section('menu')
    <div id="divListarTrabalhos" style="display: block">

        <div class="row">
            <div class="col-sm-12">
                <h2 class="">Trabalhos da modalidade: {{$modalidade->nome}}</h2>
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
                <form method="GET" action="{{ route('coord.listarTrabalhosModalidades') }}">
                    <input type="hidden" name="eventoId" value="{{ $evento->id }}">
                    <input type="hidden" name="modalidadeId" value="{{ $modalidade->id }}">
                    <input type="hidden" name="column" value="{{ request('column', 'titulo') }}">
                    <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}">
                    <input type="hidden" name="status" value="{{ request('status', 'rascunho') }}">

                    <div class="row">
                        <div class="col-md-10">
                            <label for="titulo" class="form-label">Buscar por Título</label>
                            <input type="text" class="form-control" name="titulo" value="{{ request('titulo') }}" placeholder="Digite o título do trabalho...">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Buscar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Filtro de Status --}}
        <div class="btn-group mb-2" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Opções de Filtro: {{ request('status', 'rascunho') == 'rascunho' ? 'Todos' : 'Arquivados' }}
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item" href="{{ route('coord.listarTrabalhosModalidades', ['eventoId' => $evento->id, 'modalidadeId' => $modalidade->id, 'status' => 'rascunho']) }}">Todos</a>
                    <a class="dropdown-item" href="{{ route('coord.listarTrabalhosModalidades', ['eventoId' => $evento->id, 'modalidadeId' => $modalidade->id, 'status' => 'arquivado']) }}">Arquivados</a>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-sm table-hover table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                         @foreach ($modalidade->midiasExtra as $midia)
                            <th scope="col">{{$midia->nome}}</th>
                        @endforeach
                        <th>Área</th>
                        <th>Autor</th>
                        @if ($modalidade->apresentacao)
                            <th scope="col">Apresentação</th>
                        @endif
                        <th>Avaliadores</th>
                        <th>Avaliações</th>
                        <th>Atribuir</th>
                        <th>Arquivar</th>
                        <th>Excluir</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trabalhos as $trabalho)
                        <tr>
                            <td>{{ $trabalho->id }}</td>
                            <td>
                                @if ($trabalho->tem_arquivo)
                                    <a href="{{route('downloadTrabalho', ['id' => $trabalho->id])}}">{{$trabalho->titulo}}</a>
                                @else
                                    {{$trabalho->titulo}}
                                @endif
                            </td>
                            @foreach ($modalidade->midiasExtra as $midia)
                                <td>
                                    @if($trabalho->midias_extra_verificadas->has($midia->id))
                                        <a href="{{route('downloadMidiaExtra', ['id' => $trabalho->id, 'id_midia' => $midia->id])}}" >
                                            <img src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px" alt="Baixar mídia extra">
                                        </a>
                                    @endif
                                </td>
                            @endforeach
                            <td>{{ $trabalho->area->nome }}</td>
                            <td>{{ $trabalho->autor->name }}</td>
                            @if ($modalidade->apresentacao)
                                <td>{{$trabalho->tipo_apresentacao}}</td>
                            @endif
                            <td>{{ $trabalho->atribuicoes_count }}</td>
                            <td>{{ $trabalho->quantidade_avaliacoes }}</td>
                            <td style="text-align:center">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalTrabalho{{$trabalho->id}}"><img src="{{ asset('img/icons/documento.svg') }}" width="20" alt="Atribuir"></a>
                            </td>
                            <td style="text-align:center">
                                @if ($trabalho->status == 'arquivado')
                                    <a href="{{ route('trabalho.status', [$trabalho->id, 'rascunho']) }}"><img src="{{ asset('img/icons/archive.png') }}" width="20" alt="Desarquivar"></a>
                                @else
                                    <a href="{{ route('trabalho.status', [$trabalho->id, 'arquivado'] ) }}"><img src="{{ asset('img/icons/archive.png') }}" width="20" alt="Arquivar"></a>
                                @endif
                            </td>
                            <td style="text-align:center">
                                @if ($trabalho->status == 'arquivado')
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalExcluirTrabalho_{{$trabalho->id}}"><img src="{{ asset('img/icons/lixo.png') }}" width="20" alt="Excluir"></a>
                                @endif
                            </td>
                            <td style="text-align:center">
                                <a href="{{ route('coord.trabalho.edit', ['id' => $trabalho->id]) }}"><img src="{{ asset('img/icons/edit-regular.svg') }}" width="20" alt="Editar"></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">Nenhum trabalho encontrado para esta modalidade.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @foreach ($trabalhos as $trabalho)
            <x-modal-adicionar-revisor :trabalho="$trabalho" :evento="$evento" />
            <x-modal-excluir-trabalho :trabalho="$trabalho" />
        @endforeach

        @if($trabalhos->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $trabalhos->links() }}
            </div>
        @endif
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
