@extends('coordenador.detalhesEvento')

@section('menu')
    <!-- Trabalhos -->
    <div id="divListarTrabalhos" style="display: block">

      <div class="row ">
        <div class="col-sm-6">
            <h1 class="">Trabalhos por Eixo</h1>
        </div>

        <div class="col-sm-3"></div>
        <div class="col-sm-3">
            <div class="row mt-1">
                <a class="btn btn-primary col-sm" href="{{route('evento.downloadResumos', $evento)}}">Baixar resumos</a>
            </div>
            <div class="row mt-1">
                <a class="btn btn-primary col-sm" href="{{route('evento.downloadTrabalhos', $evento)}}">Exportar trabalhos .csv</a>
            </div>
            <div class="row mt-1">
                <a class="btn btn-primary col-sm" href="{{route('evento.downloadTrabalhosAprovadosPDF', $evento)}}">
                    Lista de Trabalhos Aprovados (PDF)
                </a>
            </div>
        </div>
      </div>

      <!-- Filtro de Eixo -->
      <div class="row mb-3">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Filtrar por Eixo</h5>
              <form method="GET" action="{{ route('coord.listarTrabalhosPorEixo', ['titulo', 'asc', $status]) }}">

                <input type="hidden" name="eventoId" value="{{ $evento->id }}">
                <div class="row">
                  <div class="col-sm-6">
                    <label for="eixo_id" class="form-label">Selecione o eixo:</label>
                    <select class="form-control" id="eixo_id" name="eixo_id" onchange="this.form.submit()">
                      <option value="">-- Selecione um eixo --</option>
                      @foreach($areas as $area)
                        <option value="{{ $area->id }}" {{ $eixoSelecionado == $area->id ? 'selected' : '' }}>
                          {{ $area->nome }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-sm-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

    {{-- Tabela Trabalhos --}}
    @if($eixoSelecionado)
        <div class="btn-group mb-2" role="group" aria-label="Button group with nested dropdown">

            <div class="btn-group" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Opções
            </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <a class="dropdown-item" href="{{route('coord.listarTrabalhosPorEixo',[ 'titulo', 'asc', 'rascunho']) }}?eventoId={{ $evento->id }}{{ $eixoSelecionado ? '&eixo_id=' . $eixoSelecionado : '' }}">
                    Todos
                </a>
                <a class="dropdown-item" href="{{route('coord.listarTrabalhosPorEixo',[ 'titulo', 'asc', 'arquivado']) }}?eventoId={{ $evento->id }}{{ $eixoSelecionado ? '&eixo_id=' . $eixoSelecionado : '' }}">
                    Arquivados
                </a>
                <a class="dropdown-item" href="{{route('coord.listarTrabalhosPorEixo',[ 'titulo', 'asc', 'no_revisor']) }}?eventoId={{ $evento->id }}{{ $eixoSelecionado ? '&eixo_id=' . $eixoSelecionado : '' }}">
                    Sem avaliador
                </a>
                <a class="dropdown-item" href="{{route('coord.listarTrabalhosPorEixo',[ 'titulo', 'asc', 'with_revisor']) }}?eventoId={{ $evento->id }}{{ $eixoSelecionado ? '&eixo_id=' . $eixoSelecionado : '' }}">
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
    @else
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Selecione um eixo para visualizar os trabalhos</h5>
                        <p class="card-text">Escolha um eixo no filtro acima para começar a visualizar os trabalhos correspondentes.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if($eixoSelecionado && $modalidades->isNotEmpty())
        @foreach ($modalidades as $modalidade)
            @if($modalidade->trabalhos_count > 0)
                <div class="row justify-content-center">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Modalidade: <span class="card-subtitle mb-2 text-muted" >{{$modalidade->nome}} ( {{ $modalidade->trabalhos_count }} )</span></h5>
                        <div class="row table-trabalhos">
                            <div class="col-sm-12">
                                <form action="{{route('atribuicao.check')}}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-12">
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        </div>
                                    </div>

                                    <input type="hidden" name="eventoId" value="{{$evento->id}}">
                                    <br>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover table-striped">
                                            <thead>
                                            <tr>
                                                <th class="col-md-1">
                                                    ID
                                                    <a href="{{route('coord.listarTrabalhosPorEixo',[ 'id', 'asc', $status]) }}?eventoId={{ $evento->id }}{{ $eixoSelecionado ? '&eixo_id=' . $eixoSelecionado : '' }}">
                                                        <img class="" src="{{asset('img/icons/sobe.png')}}" style="width:10px">
                                                    </a>
                                                    <a href="{{route('coord.listarTrabalhosPorEixo',[ 'id', 'desc', $status]) }}?eventoId={{ $evento->id }}{{ $eixoSelecionado ? '&eixo_id=' . $eixoSelecionado : '' }}">
                                                        <img class="" src="{{asset('img/icons/desce.png')}}" style="width:10px">
                                                    </a>
                                                </th>
                                                <th scope="col">
                                                    Título
                                                    <a href="{{route('coord.listarTrabalhosPorEixo',[ 'titulo', 'asc', $status]) }}?eventoId={{ $evento->id }}{{ $eixoSelecionado ? '&eixo_id=' . $eixoSelecionado : '' }}">
                                                        <img class="" src="{{asset('img/icons/sobe.png')}}" style="width:10px">
                                                    </a>
                                                    <a href="{{route('coord.listarTrabalhosPorEixo',[ 'titulo', 'desc', $status]) }}?eventoId={{ $evento->id }}{{ $eixoSelecionado ? '&eixo_id=' . $eixoSelecionado : '' }}">
                                                        <img class="" src="{{asset('img/icons/desce.png')}}" style="width:10px">
                                                    </a>
                                                </th>
                                                <th scope="col">
                                                    Área
                                                    <a href="{{route('coord.listarTrabalhosPorEixo',[ 'areaId', 'asc', $status]) }}?eventoId={{ $evento->id }}{{ $eixoSelecionado ? '&eixo_id=' . $eixoSelecionado : '' }}">
                                                        <img class="" src="{{asset('img/icons/sobe.png')}}" style="width:10px">
                                                    </a>
                                                    <a href="{{route('coord.listarTrabalhosPorEixo',[ 'areaId', 'desc', $status]) }}?eventoId={{ $evento->id }}{{ $eixoSelecionado ? '&eixo_id=' . $eixoSelecionado : '' }}">
                                                        <img class="" src="{{asset('img/icons/desce.png')}}" style="width:10px">
                                                    </a>
                                                </th>
                                                <th scope="col">
                                                    Autor
                                                    <a href="{{route('coord.listarTrabalhosPorEixo',[ 'autor', 'asc', $status]) }}?eventoId={{ $evento->id }}{{ $eixoSelecionado ? '&eixo_id=' . $eixoSelecionado : '' }}">
                                                        <img class="" src="{{asset('img/icons/sobe.png')}}" style="width:10px">
                                                    </a>
                                                    <a href="{{route('coord.listarTrabalhosPorEixo',[ 'autor', 'desc', $status]) }}?eventoId={{ $evento->id }}{{ $eixoSelecionado ? '&eixo_id=' . $eixoSelecionado : '' }}">
                                                        <img class="" src="{{asset('img/icons/desce.png')}}" style="width:10px">
                                                    </a>
                                                </th>
                                                @foreach ($modalidade->midiasExtra as $midia)
                                                    <th scope="col">{{$midia->nome}}</th>
                                                @endforeach
                                                @if ($modalidade->apresentacao)
                                                    <th scope="col">Apresentação</th>
                                                @endif
                                                <th scope="col">Avaliadores</th>
                                                <th scope="col">Avaliações</th>
                                                <th scope="col">
                                                    Data
                                                    <a href="{{route('coord.listarTrabalhosPorEixo',[ 'created_at', 'asc', $status]) }}?eventoId={{ $evento->id }}{{ $eixoSelecionado ? '&eixo_id=' . $eixoSelecionado : '' }}">
                                                        <img class="" src="{{asset('img/icons/sobe.png')}}" style="width:10px">
                                                    </a>
                                                    <a href="{{route('coord.listarTrabalhosPorEixo',[ 'created_at', 'desc', $status]) }}?eventoId={{ $evento->id }}{{ $eixoSelecionado ? '&eixo_id=' . $eixoSelecionado : '' }}">
                                                        <img class="" src="{{asset('img/icons/desce.png')}}" style="width:10px">
                                                    </a>
                                                </th>
                                                <th scope="col">Atribuir</th>
                                                @can('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento)
                                                <th scope="col">Arquivar</th>
                                                    <th scope="col">Excluir</th>
                                                    <th scope="col">Editar</th>
                                                @endcan
                                            </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($modalidade->trabalho as $trabalho)
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
                                                            <span class="d-inline-block" class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="{{$trabalho->area->nome}}" style="max-width: 150px;">
                                                            {{$trabalho->area->nome}}
                                                            </span>

                                                        </td>
                                                        <td>{{$trabalho->autor->name}}</td>
                                                        @foreach ($modalidade->midiasExtra as $midia)
                                                            <td>
                                                                @if($trabalho->midiasExtra()->where('midia_extra_id', $midia->id)->first() != null)
                                                                    <a href="{{route('downloadMidiaExtra', ['id' => $trabalho->id, 'id_midia' => $midia->id])}}">
                                                                        <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="{{$midia->nome}}" style="max-width: 150px;">
                                                                            <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                                                                        </span>
                                                                    </a>
                                                                @endif
                                                            </td>
                                                        @endforeach
                                                        @if ($modalidade->apresentacao)
                                                            <td>{{$trabalho->tipo_apresentacao}}</td>
                                                        @endif
                                                        <td>
                                                            {{ $trabalho->atribuicoes()->count() }}
                                                        </td>
                                                        <td>{{ $trabalho->getQuantidadeAvaliacoes() }}</td>
                                                        <td>{{ date("d/m/Y H:i", strtotime($trabalho->created_at)) }}</td>
                                                        <td style="text-align:center">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalTrabalho{{$trabalho->id}}">
                                                                <img src="{{asset('img/icons/documento.svg')}}" class="icon-card" width="20" alt="atribuir">
                                                            </a>
                                                        </td>
                                                        <td style="text-align:center">
                                                            @can('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento)

                                                                @if ($trabalho->status == 'arquivado')
                                                                <a href="{{ route('trabalho.status', [$trabalho->id, 'rascunho']) }}" >
                                                                    <i class="fas fa-folder-open"></i>
                                                                </a>
                                                                @else
                                                                <a href="{{ route('trabalho.status', [$trabalho->id, 'arquivado'] ) }}" >
                                                                    <img src="{{asset('img/icons/archive.png')}}" class="icon-card" width="20" alt="Arquivar">
                                                                </a>
                                                                @endif
                                                            @endcan
                                                        </td>
                                                        <td style="text-align:center">
                                                            @can('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento)
                                                                @if ($trabalho->status == 'arquivado')
                                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalExcluirTrabalho_{{$trabalho->id}}">
                                                                        <img src="{{asset('img/icons/lixo.png')}}" class="icon-card" width="20" alt="Excluir">
                                                                    </a>
                                                                @endif
                                                            @endcan
                                                        </td>
                                                        <td style="text-align:center">
                                                            @can('isCoordenadorOrCoordenadorDaComissaoCientifica', $evento)
                                                                <a href="{{ route('coord.trabalho.edit', ['id' => $trabalho->id]) }}" >
                                                                    <img src="{{asset('img/icons/edit-regular.svg')}}" class="icon-card" width="20" alt="Editar">
                                                                </a>
                                                            @endcan
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            @endif
        @endforeach
    @elseif($eixoSelecionado)
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Nenhum trabalho encontrado</h5>
                        <p class="card-text">Não foram encontrados trabalhos para o eixo selecionado.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
<!-- End Trabalhos -->

@if($eixoSelecionado)
    @foreach ($modalidades as $modalidade)
        @if($modalidade->trabalho)
            @foreach ($modalidade->trabalho as $trabalho)
                <!-- Modal Trabalho -->
                <x-modal-adicionar-revisor :trabalho="$trabalho" :evento="$evento" />
                <x-modal-excluir-trabalho :trabalho="$trabalho" />
            @endforeach
        @endif
    @endforeach
@endif

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
