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
        <div class="row mt-5">
            <div class="col-md-1">
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
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
            </div>

            <div class="col-md-11">
                <form method="GET" class="mb-3">
                    <div class="input-group">
                    <input type="search" name="filter[q]" value="{{ request('filter.q') }}"
                        class="form-control" placeholder="Buscar por ID, título ou autor...">
                    <input type="hidden" name="eventoId" id="eventoId" value="{{ $evento->id }}">
                    <button class="btn btn-outline-primary" type="submit" aria-label="Buscar trabalhos">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <span class="ms-1">Buscar</span>
                    </button>

                    </div>
                </form>
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
                                                    {{ $trabalho->revisores()->count() }}
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
    @foreach ($trabalhos as $trabalho)
    <!-- Modal Trabalho -->
    <div class="modal fade" id="modalTrabalho{{ $trabalho->id }}" tabindex="-1" role="dialog"
        aria-labelledby="labelModalTrabalho{{ $trabalho->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #114048ff; color: white;">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Trabalho</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="color: white;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        @if (session('success'))
                            <div class="col-sm-12">
                                <div class="alert alert-success">
                                    <p>{{ session('success') }}</p>
                                </div>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="col-sm-12">
                                <div class="alert alert-danger">
                                    <p>{{ session('error') }}</p>
                                </div>
                            </div>
                        @endif
                        <div class="col-sm-6">
                            <h5>{{ __('Título') }}</h5>
                            <p id="tituloTrabalho">{{ $trabalho->titulo }}</p>
                        </div>
                        <div class="col-sm-6">
                            <h5>{{ __('Autores') }}</h5>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">E-mail</th>
                                        <th scope="col">{{ __('Nome') }}</th>
                                        <th scope="col">{{ __('Vinculação') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $trabalho->autor->email }}</td>
                                        <td>{{ $trabalho->autor->name }}</td>
                                        <td>{{ __('Autor') }}</td>
                                    </tr>
                                    @foreach ($trabalho->coautors as $coautor)
                                        @if ($coautor->user->id != $trabalho->autorId)
                                            <tr>
                                                <td>{{ $coautor->user->email }}</td>
                                                <td>{{ $coautor->user->name }}</td>
                                                <td>
                                                    {{ __('Coautor') }}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if ($trabalho->resumo != '')
                        <div class="row justify-content-center">
                            <div class="col-sm-12">
                                <h5>{{ ('Resumo') }}</h5>
                                <p id="resumoTrabalho">{{ $trabalho->resumo }}</p>
                            </div>
                        </div>
                    @endif
                    @if (count($trabalho->revisores) > 0)
                        <div class="row justify-content-start">
                            <div class="col-sm-12">
                                <h5>{{ __('Avaliadores atribuídos ao trabalho') }}</h5>
                            </div>
                            @foreach ($trabalho->revisores as $i => $revisor)
                                <div class="col-sm-4">
                                    <div class="card" style="width: 13.5rem; text-align: center;">
                                        <img class="" src="{{ asset('img/icons/user.png') }}" width="100px" alt="Revisor"
                                            style="position: relative; left: 30%; top: 10px;">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $revisor->user->name }}</h6>
                                            <strong>E-mail</strong>
                                            <p class="card-text">{{ $revisor->user->email }}</p>
                                            <form action="{{ route('atribuicao.delete', ['id' => $revisor->id]) }}" method="post">
                                                @csrf
                                                <input type="hidden" name="eventoId" value="{{ $evento->id }}">
                                                <input type="hidden" name="trabalhoId" value="{{ $trabalho->id }}">
                                                <button type="submit" class="btn btn-primary button-prevent-multiple-submits"
                                                    id="removerRevisorTrabalho">
                                                    {{ ('Remover Avaliador') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-sm-12">
                            <h5>{{ __('Adicionar Avaliador') }}</h5>
                        </div>
                    </div>
                    <form action="{{ route('distribuicaoManual') }}" method="post">
                        @csrf
                        <input type="hidden" name="trabalhoId" value="{{ $trabalho->id }}">
                        <input type="hidden" name="eventoId" value="{{ $evento->id }}">
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="form-group">
                                    <select name="revisorId" class="form-control" id="selectRevisorTrabalho">
                                        <option value="" disabled selected>-- {{ __('E-mail do avaliador') }} --</option>
                                        @foreach ($evento->revisors()->where([['modalidadeId', $trabalho->modalidade->id], ['areaId', $trabalho->area->id]])->get() as $revisor)
                                            @if (
                                                !$trabalho->revisores->contains($revisor) &&
                                                    is_null($trabalho->coautors->where('autorId', $revisor->user_id)->first()) &&
                                                    $trabalho->autorId != $revisor->user_id)
                                                    @php
                                                        $get = $revisor->user->revisorWithCounts()->where('evento_id', $evento->id)->get();
                                                        $processando = $get->sum('processando_count');
                                                        $avaliados = $get->sum('avaliados_count') + $processando;
                                                    @endphp
                                                <option value="{{ $revisor->id }}">{{ $revisor->user->name }}
                                                    ({{ $revisor->user->email }})
                                                    ({{ trans_choice('messages.qtd_revisores', $processando, ['value' => $processando]) }})
                                                    ({{ trans_choice('messages.qtd_trabalhos_atribuidos', $avaliados, ['value' => $avaliados]) }})
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-primary button-prevent-multiple-submits"
                                    id="addRevisorTrabalho">
                                    <i class="spinner fa fa-spinner fa-spin" style="display: none;"></i>{{ __('Adicionar Avaliador') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @endforeach
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
