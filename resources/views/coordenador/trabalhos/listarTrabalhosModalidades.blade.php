@extends('coordenador.detalhesEvento')

@section('menu')
    <!-- Trabalhos -->
    <div id="divListarTrabalhos" style="display: block">

      <div class="row ">
        <div class="col-sm-12">
            <h2 class="">Trabalhos da modalidade {{$modalidade->nome}}</h2>
        </div>

        {{-- <div class="col-sm-3"></div>
        <div class="col-sm-3">
          <form method="GET" action="{{route('distribuicao')}}">
            <input type="hidden" name="eventoId" value="{{$evento->id}}">
            <button onclick="event.preventDefault();" data-toggle="modal" data-target="#modalDistribuicaoAutomatica" class="btn btn-primary" style="width:100%">
              {{ __('Distribuir trabalhos') }}
            </button>
          </form>

        </div> --}}
      </div>

    {{-- Tabela Trabalhos --}}
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
          <div class="btn-group mb-2" role="group" aria-label="Button group with nested dropdown">

            <div class="btn-group" role="group">
              <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Opções
              </button>
              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <a class="dropdown-item" href="{{route('coord.listarTrabalhosModalidades',[ 'eventoId' => $evento->id, 'modalidadeId' => $modalidade->id, 'titulo', 'asc', 'rascunho'])}}">
                    Todos
                </a>
                <a class="dropdown-item" href="{{route('coord.listarTrabalhosModalidades',[ 'eventoId' => $evento->id, 'modalidadeId' => $modalidade->id, 'titulo', 'asc', 'arquivado'])}}">
                    Arquivados
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

          <input type="hidden" name="eventoId" value="{{$evento->id}}">
          <br>
          <div class="table-responsive">
              <table class="table table-sm table-hover table-striped">
                <thead>
                  <tr>
                    <th scope="col">
                        <input type="checkbox" id="selectAllCheckboxes" onclick="marcarCheckboxes()">
                        <label for="selectAllCheckboxes" style="margin-bottom: 0px;">Selecionar</label>
                    </th>
                    <th scope="col">ID
                      <a href="{{route('coord.listarTrabalhosModalidades',[ 'eventoId' => $evento->id, 'modalidadeId' => $modalidade->id, 'id', 'asc', 'rascunho'])}}">
                        <i class="fas fa-arrow-alt-circle-up"></i>
                      </a>
                      <a href="{{route('coord.listarTrabalhosModalidades',[ 'eventoId' => $evento->id, 'modalidadeId' => $modalidade->id, 'id', 'desc', 'rascunho'])}}">
                        <i class="fas fa-arrow-alt-circle-down"></i>
                      </a>
                    </th>
                    <th scope="col">
                      Título
                      <a href="{{route('coord.listarTrabalhosModalidades',[ 'eventoId' => $evento->id, 'modalidadeId' => $modalidade->id, 'titulo', 'asc', 'rascunho'])}}">
                        <i class="fas fa-arrow-alt-circle-up"></i>
                      </a>
                      <a href="{{route('coord.listarTrabalhosModalidades',[ 'eventoId' => $evento->id, 'modalidadeId' => $modalidade->id, 'titulo', 'desc', 'rascunho'])}}">
                        <i class="fas fa-arrow-alt-circle-down"></i>
                      </a>
                    </th>
                    @if ($modalidade->midiasExtra)
                        @foreach ($modalidade->midiasExtra as $midia)
                            <th scope="col">{{$midia->nome}}</th>
                        @endforeach
                    @endif
                    <th scope="col">
                      Área
                      <a href="{{route('coord.listarTrabalhosModalidades',[ 'eventoId' => $evento->id, 'modalidadeId' => $modalidade->id, 'areaId', 'asc', 'rascunho'])}}">
                        <i class="fas fa-arrow-alt-circle-up"></i>
                      </a>
                      <a href="{{route('coord.listarTrabalhosModalidades',[ 'eventoId' => $evento->id, 'modalidadeId' => $modalidade->id, 'areaId', 'desc', 'rascunho'])}}">
                        <i class="fas fa-arrow-alt-circle-down"></i>
                      </a>
                    </th>
                    <th scope="col">
                      Autor
                      <a href="{{route('coord.listarTrabalhosModalidades',[ 'eventoId' => $evento->id, 'modalidadeId' => $modalidade->id, 'autor', 'asc', 'rascunho'])}}">
                        <i class="fas fa-arrow-alt-circle-up"></i>
                      </a>
                      <a href="{{route('coord.listarTrabalhosModalidades',[ 'eventoId' => $evento->id, 'modalidadeId' => $modalidade->id, 'autor', 'desc', 'rascunho'])}}">
                        <i class="fas fa-arrow-alt-circle-down"></i>
                      </a>
                    </th>
                    @if ($modalidade->apresentacao)
                    <th scope="col">Apresentação</th>
                    @endif
                    <th scope="col">Avaliadores</th>
                    <th scope="col">Avaliações</th>
                    {{--<th scope="col">Data</th>--}}
                    <th scope="col" style="text-align:center">Atribuir</th>
                    <th scope="col" style="text-align:center">Arquivar</th>
                    <th scope="col" style="text-align:center">Excluir</th>
                    <th scope="col" style="text-align:center">Editar</th>
                  </tr>
                </thead>

                <tbody>
                  @php $i = 0; @endphp
                  @foreach($trabalhos as $trabalho)

                  <tr>
                    <td style="text-align:center">
                      <input type="checkbox" aria-label="Checkbox for following text input" name="id[]" value="{{$trabalho->id}}" class="trabalhos">
                    </td>
                    <td>{{ $trabalho->id }}</td>
                      <td>
                        @if ($trabalho->arquivo && count($trabalho->arquivo) > 0)
                            <a href="{{route('downloadTrabalho', ['id' => $trabalho->id])}}">
                                <span class="d-inline-block text-truncate" tabindex="0" data-toggle="tooltip" title="{{$trabalho->titulo}}" style="max-width: 150px;">
                                    {{$trabalho->titulo}}
                                </span>
                            </a>
                        @else
                            <span class="d-inline-block text-truncate" tabindex="0" data-toggle="tooltip" title="{{$trabalho->titulo}}" style="max-width: 150px;">
                                {{$trabalho->titulo}}
                            </span>
                        @endif
                      </td>
                        @if ($modalidade->midiasExtra)
                            @foreach ($modalidade->midiasExtra as $midia)
                                <td>
                                    @if($trabalho->midiasExtra()->where('midia_extra_id', $midia->id)->first() != null)
                                        <a href="{{route('downloadMidiaExtra', ['id' => $trabalho->id, 'id_midia' => $midia->id])}}" >
                                            <span class="d-inline-block text-truncate" tabindex="0" data-toggle="tooltip" title="{{$midia->nome}}" style="max-width: 150px;">
                                                <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" style="width:20px">
                                            </span>
                                        </a>
                                    @endif
                                </td>
                            @endforeach
                        @endif
                      <td>
                        <span class="d-inline-block text-truncate" tabindex="0" data-toggle="tooltip" title="{{$trabalho->area->nome}}" style="max-width: 150px;">
                          {{$trabalho->area->nome}}
                        </span>

                      </td>
                      <td>{{$trabalho->autor->name}}</td>
                      @if ($modalidade->apresentacao)
                        <td>{{$trabalho->tipo_apresentacao}}</td>
                      @endif
                      <td>
                        {{count($trabalho->atribuicoes)}}
                      </td>
                      <td>{{$trabalho->getQuantidadeAvaliacoes()}}</td>
                      {{--<td>
                        {{ date("d/m/Y H:i", strtotime($trabalho->created_at) ) }}

                      </td>--}}
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

@foreach ($trabalhos as $trabalho)
    <!-- Modal Trabalho -->
    <x-modal-adicionar-revisor :trabalho="$trabalho" :evento="$evento" />
    <x-modal-excluir-trabalho :trabalho="$trabalho" />
@endforeach
@endsection

@section('javascript')
  @parent
  <script>
      function marcarCheckboxes() {
          $(".trabalhos").prop('checked', $('#selectAllCheckboxes').is(":checked"));
      }
      const id = {!! json_encode(old('trabalhoId')) !!};
        $(document).ready(function(){
            if(id != null){
                $('#modalTrabalho'+id).modal('show');
            }
        });
  </script>
@endsection
