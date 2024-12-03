 @extends('coordenador.detalhesEvento')

@section('menu')
    <!-- Trabalhos -->
    <div id="divListarTrabalhos" style="display: block">

      <div class="row ">
        <div class="col-sm-6">
            <h1 class="">Trabalhos</h1>
        </div>

        <div class="col-sm-3"></div>
        <div class="col-sm-3">
          {{-- <form method="GET" action="{{route('distribuicao')}}">
            <input type="hidden" name="eventoId" value="{{$evento->id}}">

            <div class="row">
                <button onclick="event.preventDefault();" data-toggle="modal" data-target="#modalDistribuicaoAutomatica" class="btn btn-primary" style="width:100%">
                    {{ __('Distribuir trabalhos') }}
                  </button>
            </div>

          </form> --}}
          <div class="row mt-1">
            <a class="btn btn-primary col-sm" href="{{route('evento.downloadResumos', $evento)}}">Baixar resumos</a>
          </div>
          <div class="row mt-1">
            <a class="btn btn-primary col-sm" href="{{route('evento.downloadTrabalhos', $evento)}}">Exportar trabalhos .csv</a>
          </div>
        </div>
      </div>

    {{-- Tabela Trabalhos --}}
    <div class="btn-group mb-2" role="group" aria-label="Button group with nested dropdown">

        <div class="btn-group" role="group">
        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Opções
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
            <a class="dropdown-item" href="{{route('coord.listarTrabalhos',[ 'eventoId' => $evento->id, 'titulo', 'asc', 'rascunho'])}}">
                Todos
            </a>
            <a class="dropdown-item" href="{{route('coord.listarTrabalhos',[ 'eventoId' => $evento->id, 'titulo', 'asc', 'arquivado'])}}">
                Arquivados
            </a>
            <a class="dropdown-item" href="{{route('coord.listarTrabalhos',[ 'eventoId' => $evento->id, 'titulo', 'asc', 'no_revisor'])}}">
                Sem avaliador
            </a>
            <a class="dropdown-item" href="{{route('coord.listarTrabalhos',[ 'eventoId' => $evento->id, 'titulo', 'asc', 'with_revisor'])}}">
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
    @foreach ($modalidades as $modalidade)
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Modalidade: <span class="card-subtitle mb-2 text-muted" >{{$modalidade->nome}} ( {{ $modalidade->trabalhos_count }} )</span></h5>
                        <div class="row table-trabalhos">
                            <div class="col-sm-12">
                                <form action="{{route('atribuicao.check')}}" method="post">
                                    @csrf
                                    {{-- <div class="row">
                                        <div class="col-sm-9"></div>
                                        <div class="col-sm-3">
                                        <button type="submit" class="btn btn-primary" style="width:100%">
                                            {{ __('Distribuir em lote') }}
                                        </button>
                                        </div>
                                    </div> --}}
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
                                                {{-- <th scope="col" style="text-align:center">
                                                    @if(!is_null($trabalhos->first()))
                                                    <input type="checkbox" id="selectAllCheckboxes{{$trabalhos[0]->modalidade->id}}" onclick="marcarCheckboxes({{$trabalhos[0]->modalidade->id}})">
                                                    <label for="selectAllCheckboxes{{$trabalhos[0]->modalidade->id}}" style="margin-bottom: 0px;">Selecionar</label>
                                                    @else
                                                    Selecionar
                                                    @endif
                                                </th> --}}
                                                <th scope="col">ID</th>
                                                <th scope="col">
                                                    Título
                                                    <a href="{{route('coord.listarTrabalhos',[ 'eventoId' => $evento->id, 'titulo', 'asc', $status])}}">
                                                        <i class="fas fa-arrow-alt-circle-up"></i>
                                                    </a>
                                                    <a href="{{route('coord.listarTrabalhos',[ 'eventoId' => $evento->id, 'titulo', 'desc', $status])}}">
                                                        <i class="fas fa-arrow-alt-circle-down"></i>
                                                    </a>
                                                </th>
                                                <th scope="col">
                                                    Área
                                                    <a href="{{route('coord.listarTrabalhos',[ 'eventoId' => $evento->id, 'areaId', 'asc', $status])}}">
                                                        <i class="fas fa-arrow-alt-circle-up"></i>
                                                    </a>
                                                    <a href="{{route('coord.listarTrabalhos',[ 'eventoId' => $evento->id, 'areaId', 'desc', $status])}}">
                                                        <i class="fas fa-arrow-alt-circle-down"></i>
                                                    </a>
                                                </th>
                                                <th scope="col">
                                                    Autor
                                                    <a href="{{route('coord.listarTrabalhos',[ 'eventoId' => $evento->id, 'autor', 'asc', $status])}}">
                                                        <i class="fas fa-arrow-alt-circle-up"></i>
                                                    </a>
                                                    <a href="{{route('coord.listarTrabalhos',[ 'eventoId' => $evento->id, 'autor', 'desc', $status])}}">
                                                        <i class="fas fa-arrow-alt-circle-down"></i>
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
                                                    <a href="{{route('coord.listarTrabalhos',[ 'eventoId' => $evento->id, 'created_at', 'asc', $status])}}">
                                                        <i class="fas fa-arrow-alt-circle-up"></i>
                                                    </a>
                                                    <a href="{{route('coord.listarTrabalhos',[ 'eventoId' => $evento->id, 'created_at', 'desc', $status])}}">
                                                        <i class="fas fa-arrow-alt-circle-down"></i>
                                                    </a>
                                                </th>
                                                <th scope="col">Atribuir</th>
                                                <th scope="col">Arquivar</th>
                                                <th scope="col">Excluir</th>
                                                <th scope="col">Editar</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($modalidade->trabalho as $trabalho)
                                                    <tr id="trab{{$trabalho->id}}">
                                                        {{-- <td style="text-align:center">
                                                            <input type="checkbox" aria-label="Checkbox for following text input" name="id[]" class="modalidade{{$trabalho->modalidade->id}}" value="{{$trabalho->id}}">
                                                        </td> --}}
                                                        <td>{{ $trabalho->id }}</td>
                                                        <td>
                                                            @if ($trabalho->arquivo && count($trabalho->arquivo) > 0)
                                                                <a href="{{route('downloadTrabalho', ['id' => $trabalho->id])}}">
                                                                    <span class="d-inline-block" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="{{$trabalho->titulo}}">
                                                                        {{$trabalho->titulo}}
                                                                    </span>
                                                                </a>
                                                            @else
                                                                <span class="d-inline-block" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="{{$trabalho->titulo}}">
                                                                    {{$trabalho->titulo}}
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="d-inline-block" class="d-inline-block" tabindex="0" data-toggle="tooltip" title="{{$trabalho->area->nome}}" style="max-width: 150px;">
                                                            {{$trabalho->area->nome}}
                                                            </span>

                                                        </td>
                                                        <td>{{$trabalho->autor->name}}</td>
                                                        @foreach ($modalidade->midiasExtra as $midia)
                                                            <td>
                                                                @if($trabalho->midiasExtra()->where('midia_extra_id', $midia->id)->first() != null)
                                                                    <a href="{{route('downloadMidiaExtra', ['id' => $trabalho->id, 'id_midia' => $midia->id])}}">
                                                                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="{{$midia->nome}}" style="max-width: 150px;">
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
                                                            <a href="#" data-toggle="modal" data-target="#modalTrabalho{{$trabalho->id}}">
                                                                <i class="fas fa-file-alt"></i>
                                                            </a>
                                                        </td>
                                                        <td style="text-align:center">
                                                            @if ($trabalho->status == 'arquivado')
                                                                <a href="{{ route('trabalho.status', [$trabalho->id, 'rascunho']) }}" class="btn btn-info" >
                                                                    <i class="fas fa-folder-open"></i>
                                                                </a>
                                                            @else
                                                                <a href="{{ route('trabalho.status', [$trabalho->id, 'arquivado'] ) }}" class="btn btn-info" >
                                                                    <i class="fas fa-archive"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                        <td style="text-align:center">
                                                            @if ($trabalho->status == 'arquivado')
                                                                <a href="#" data-toggle="modal" data-target="#modalExcluirTrabalho_{{$trabalho->id}}">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                        <td style="text-align:center">
                                                            <a href="{{ route('coord.trabalho.edit', ['id' => $trabalho->id]) }}" >
                                                                <i class="fas fa-edit"></i>
                                                            </a>
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
    @endforeach
<!-- End Trabalhos -->
<!-- Modal Trabalho -->
{{-- <div class="modal fade" id="modalDistribuicaoAutomatica" tabindex="-1" role="dialog" aria-labelledby="modalDistribuicaoAutomatica" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #114048ff; color: white;">
        <h5 class="modal-title" id="exampleModalCenterTitle">Distribuir trabalhos automaticamente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="GET" action="{{ route('distribuicaoAutomaticaPorArea') }}" id="formDistribuicaoPorArea">
        <div class="modal-body">
          <input type="hidden" name="eventoId" value="{{$evento->id}}">
          <div class="row">
            <div class="col-sm-12">
                <input type="hidden" name="distribuirTrabalhosAutomaticamente" value="{{$evento->id}}">
                <label for="areaId" class="col-form-label">{{ __('Área') }}</label>
                <select class="form-control @error('área') is-invalid @enderror" id="areaIdformDistribuicaoPorArea" name="área" required>
                    <option value="" disabled selected hidden>-- Área --</option>
                    @foreach($areas as $area)
                        <option value="{{$area->id}}">{{$area->nome}}</option>
                    @endforeach
                </select>

                @error('área')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
          </div>
          <div class="row">
              <div class="col-sm-12">
                  <label for="numeroDeRevisoresPorTrabalho" class="col-form-label">{{ __('Número de avaliadores por trabalho') }}</label>
              </div>
          </div>
          <div class="row justify-content-center">
              <div class="col-sm-12">
                  <input id="numeroDeRevisoresPorTrabalhoInput" type="number" min="1" class="form-control @error('numeroDeRevisoresPorTrabalho') is-invalid @enderror" name="numeroDeRevisoresPorTrabalho" value="{{ old('numeroDeRevisoresPorTrabalho') }}" required autocomplete="numeroDeRevisoresPorTrabalho" autofocus>

                  @error('numeroDeRevisoresPorTrabalho')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                  @enderror
              </div>

          </div>
        </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button id="numeroDeRevisoresPorTrabalhoButton" onclick="document.getElementById('formDistribuicaoPorArea').submit();" type="button" class="btn btn-primary">Distribuir</button>
      </div>
    </div>
  </div>
</div> --}}

@foreach ($modalidades as $modalidade)
    @foreach ($modalidade->trabalho as $trabalho)
        <!-- Modal Trabalho -->
        <x-modal-adicionar-revisor :trabalho="$trabalho" :evento="$evento" />
        <x-modal-excluir-trabalho :trabalho="$trabalho" />
    @endforeach
@endforeach
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
