 @extends('layouts.app')
@section('sidebar')

@endsection
@section('content')
    <!-- Trabalhos -->

    <div class="container">
        <div class="row ">
            <div class="col-sm-6">
                <h1 class="">Trabalhos</h1>
            </div>
            <div class="col-sm-3"></div>
            <div class="col-sm-3">
                <div class="row mt-1">
                    <a class="btn btn-primary col-sm" href="{{route('evento.downloadResumos', $evento)}}">Baixar resumos</a>
                </div>
                <div class="row mt-1">
                    <a class="btn btn-primary col-sm" href="{{route('evento.downloadTrabalhos', $evento)}}">Exportar Trabalhos .xlsx</a>
                </div>
                <div class="row mt-1">
                    <a class="btn btn-primary col-sm" href="{{route('evento.downloadTrabalhosAprovadosPDF', $evento)}}">
                        Lista de Trabalhos Aprovados (PDF)
                    </a>
                </div>
                <!-- <div class="row mt-1">
                    <a class="btn btn-primary col-sm" data-bs-toggle="modal" data-bs-target="#exportCertificaModal">Exportar XLSX para o Certifica</a>
                </div> -->
            </div>
        </div>
        {{-- Tabela Trabalhos --}}
        <div class="btn-group mb-2" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Opções
            </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <a class="dropdown-item" href="{{route('coord.listarTrabalhos',[ 'eventoId' => $evento->id, 'filter[status]' => 'rascunho'])}}">
                    Todos
                </a>
                <a class="dropdown-item" href="{{route('coord.listarTrabalhos',[ 'eventoId' => $evento->id, 'filter[status]' => 'arquivado'])}}">
                    Arquivados
                </a>
                <a class="dropdown-item" href="{{route('coord.listarTrabalhos',[ 'eventoId' => $evento->id, 'filter[has_revisor]' => 'false'])}}">
                    Sem avaliador
                </a>
                <a class="dropdown-item" href="{{route('coord.listarTrabalhos',[ 'eventoId' => $evento->id, 'filter[has_revisor]' => 'true'])}}">
                    Com avaliador
                </a>
                <a class="dropdown-item disabled" href="#" >
                    Submetidos
                </a>
                <a class="dropdown-item disabled" href="#" >
                    Aprovados
                </a>
                <a class="dropdown-item disabled" href="#" >
                    Corrigidos
                </a>
                <a class="dropdown-item disabled" href="#" >
                    Rascunhos
                </a>
            </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row table-trabalhos">
                    <div class="col-md-12">
                        <form action="{{route('atribuicao.check')}}" method="post">
                            @csrf
                            <input type="hidden" name="eventoId" value="{{$evento->id}}">
                            <div class="table-responsive">
                                <table class="table table-md table-hover">
                                    <thead>
                                        <tr>
                                            <th class="">
                                                @sortlink('id', 'ID')
                                            </th>
                                            <th scope="col">
                                                @sortlink('titulo', 'Título')
                                            </th>
                                            <th scope="col">
                                                Modalidade
                                            </th>
                                            <th scope="col">
                                                Área
                                            </th>
                                            <th scope="col">
                                                Autor
                                            </th>
                                            <th scope="col">Mídias Extras</th>
                                            <th scope="col">Apresentação</th>
                                            <th scope="col">Avaliadores</th>
                                            <th scope="col">Avaliações</th>
                                            <th scope="col">
                                                @sortlink('created_at', 'Data')
                                            </th>
                                            <th scope="col">Atribuir</th>
                                            @can('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento)
                                                <th scope="col">Ações</th>
                                            @endcan
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($trabalhos as $trabalho)
                                            <tr id="trab{{$trabalho->id}}">
                                                <td>{{ $trabalho->id }}</td>
                                                <td>
                                                    @if ($trabalho->arquivo && count($trabalho->arquivo) > 0)
                                                        <a href="{{route('downloadTrabalho', ['id' => $trabalho->id])}}">
                                                            <span class="d-inline-block" class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="{{$trabalho->titulo}}">
                                                                {{$trabalho->titulo}}
                                                            </span>
                                                        </a>
                                                    @else
                                                        <span class="d-inline-block" class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="{{$trabalho->titulo}}">
                                                            {{$trabalho->titulo}}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{$trabalho->modalidade->nome}}
                                                </td>
                                                <td>
                                                    <span class="d-inline-block" class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="{{$trabalho->area->nome}}" style="max-width: 150px;">
                                                        {{$trabalho->area->nome}}
                                                    </span>
                                                </td>
                                                <td>{{$trabalho->autor->name}}</td>
                                                <td>
                                                    @foreach ($trabalho->modalidade->midiasExtra as $midia)
                                                        @if($trabalho->midiasExtra()->where('midia_extra_id', $midia->id)->first() != null)
                                                            <a href="{{route('downloadMidiaExtra', ['id' => $trabalho->id, 'id_midia' => $midia->id])}}">
                                                                <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="{{$midia->nome}}" style="max-width: 150px;">
                                                                    <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                                                                </span>
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @if ($trabalho->modalidade->apresentacao)
                                                        {{$trabalho->tipo_apresentacao}}
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $trabalho->atribuicoes()->count() }}
                                                </td>
                                                <td>{{ $trabalho->getQuantidadeAvaliacoes() }}</td>
                                                <td>{{ date("d/m/Y H:i", strtotime($trabalho->created_at)) }}</td>
                                                <td class="text-center">
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalTrabalho{{$trabalho->id}}">
                                                        <img src="{{asset('img/icons/documento.svg')}}" class="icon-card" width="20" alt="atribuir">
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <span class="d-flex gap-3">
                                                        @can('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento)
                                                            <a href="{{ route('coord.trabalho.edit', ['id' => $trabalho->id]) }}" >
                                                                <i class="bi bi-pencil-square text-primary fs-5"></i>
                                                            </a>
                                                            @if ($trabalho->status == 'rascunho')
                                                                <a href="{{ route('trabalho.status', [$trabalho->id, 'arquivado'] ) }}" >
                                                                    <i class="bi bi-archive-fill text-secondary fs-5"></i>
                                                                </a>
                                                            @elseif ($trabalho->status == 'arquivado')
                                                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalExcluirTrabalho_{{$trabalho->id}}">
                                                                    <i class="bi bi-trash text-danger fs-5"></i>
                                                                </a>
                                                            @endif
                                                        @endcan
                                                    </span>
                                                </td>
                                            </tr>
                                            {{-- <x-modal-adicionar-revisor :trabalho="$trabalho" :evento="$evento" />
                                            <x-modal-excluir-trabalho :trabalho="$trabalho" /> --}}
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-5 d-flex justify-content-center">
                                    {{ $trabalhos->appends(request()->query())->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('coordenador.trabalhos.export_certifica_modal', compact('evento'))
@endsection

@section('javascript')
    @parent
    <script>

        $(function(){
            //your current click function
            $('.scroll').on('click',function(e){
                e.preventDefault();
                $('html,body').animate({
                    scrollTop:$($(this).attr('href')).offset().top + 'px'
                },1000,'swing');
            });

            // if we have anchor on the url (calling from other page)
            if(window.location.hash){
                // smooth scroll to the anchor id
                $('html,body').animate({
                    scrollTop:$(window.location.hash).offset().top - $('.navbar').first().height() - 20 + 'px'
                    },1000,'swing');
            }
        });

        function marcarCheckboxes(id) {
            $(".modalidade" + id).prop('checked', $('#selectAllCheckboxes'+id).is(":checked"));
        }
        const id = {!! json_encode(old('trabalhoId')) !!};
        $(document).ready(function(){
            if(id != null){
                $('#modalTrabalho'+id).modal('show');
            }
        });
    </script>
@endsection
