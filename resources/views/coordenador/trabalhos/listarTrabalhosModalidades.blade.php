@extends('layouts.app')
@section('sidebar')
@endsection
@section('content')
    <!-- Trabalhos -->
    <div class="container">

        <div class="row">
            <div class="col-sm-12">
                <h2 class="">Trabalhos da modalidade {{ $modalidade->nome }}</h2>
            </div>
        </div>

        {{-- Tabela Trabalhos --}}
        <div class="row table-trabalhos">
            <div class="col-sm-12">

                <form action="{{ route('atribuicao.check') }}" method="post">
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
                            <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Opções
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <a class="dropdown-item"
                                    href="{{ route('coord.listarTrabalhosModalidades', ['eventoId' => $evento->id, 'modalidadeId' => $modalidade->id, 'filter[status]' => 'rascunho']) }}">
                                    Todos
                                </a>
                                <a class="dropdown-item"
                                    href="{{ route('coord.listarTrabalhosModalidades', ['eventoId' => $evento->id, 'modalidadeId' => $modalidade->id, 'filter[status]' => 'arquivado']) }}">
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

                    <input type="hidden" name="eventoId" value="{{ $evento->id }}">
                    <br>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">
                                        <input type="checkbox" id="selectAllCheckboxes" onclick="marcarCheckboxes()">
                                        <label for="selectAllCheckboxes">Selecionar</label>
                                    </th>
                                    <th class="text-center">
                                        @sortlink('id', 'ID')
                                    </th>
                                    <th scope="col">
                                        @sortlink('titulo', 'Título')
                                    </th>
                                    @if ($modalidade->midiasExtra)
                                        @foreach ($modalidade->midiasExtra as $midia)
                                            <th scope="col">{{ $midia->nome }}</th>
                                        @endforeach
                                    @endif
                                    <th scope="col">
                                        Área
                                    </th>
                                    <th scope="col">
                                        Autor
                                    </th>
                                    @if ($modalidade->apresentacao)
                                        <th scope="col">Apresentação</th>
                                    @endif
                                    <th scope="col">Avaliadores</th>
                                    <th scope="col">Avaliações</th>
                                    {{-- <th scope="col">Data</th> --}}
                                    <th scope="col" style="text-align:center">Atribuir</th>
                                    <th scope="col" style="text-align:center">Ações</th>

                                </tr>
                            </thead>

                            <tbody>
                                @php $i = 0; @endphp
                                @foreach ($trabalhos as $trabalho)
                                    <tr>
                                        <td style="text-align:center">
                                            <input type="checkbox" aria-label="Checkbox for following text input"
                                                name="id[]" value="{{ $trabalho->id }}" class="trabalhos">
                                        </td>
                                        <td>{{ $trabalho->id }}</td>
                                        <td>
                                            @if ($trabalho->arquivo && count($trabalho->arquivo) > 0)
                                                <a href="{{ route('downloadTrabalho', ['id' => $trabalho->id]) }}">
                                                    <span class="d-inline-block text-truncate" tabindex="0"
                                                        data-bs-toggle="tooltip" title="{{ $trabalho->titulo }}"
                                                        style="max-width: 150px;">
                                                        {{ $trabalho->titulo }}
                                                    </span>
                                                </a>
                                            @else
                                                <span class="d-inline-block text-truncate" tabindex="0"
                                                    data-bs-toggle="tooltip" title="{{ $trabalho->titulo }}"
                                                    style="max-width: 150px;">
                                                    {{ $trabalho->titulo }}
                                                </span>
                                            @endif
                                        </td>
                                        @if ($modalidade->midiasExtra)
                                            @foreach ($modalidade->midiasExtra as $midia)
                                                <td>
                                                    @if ($trabalho->midiasExtra()->where('midia_extra_id', $midia->id)->first() != null)
                                                        <a
                                                            href="{{ route('downloadMidiaExtra', ['id' => $trabalho->id, 'id_midia' => $midia->id]) }}">
                                                            <span class="d-inline-block text-truncate" tabindex="0"
                                                                data-bs-toggle="tooltip" title="{{ $midia->nome }}"
                                                                style="max-width: 150px;">
                                                                <img class=""
                                                                    src="{{ asset('img/icons/file-download-solid.svg') }}"
                                                                    style="width:20px">
                                                            </span>
                                                        </a>
                                                    @endif
                                                </td>
                                            @endforeach
                                        @endif
                                        <td>
                                            <span class="d-inline-block text-truncate" tabindex="0"
                                                data-bs-toggle="tooltip" title="{{ $trabalho->area->nome }}"
                                                style="max-width: 150px;">
                                                {{ $trabalho->area->nome }}
                                            </span>
                                        </td>
                                        <td>{{ $trabalho->autor->name }}</td>
                                        @if ($modalidade->apresentacao)
                                            <td>{{ $trabalho->tipo_apresentacao }}</td>
                                        @endif
                                        <td class="text-center">
                                            {{ count($trabalho->revisores) }}
                                        </td>
                                        <td class="text-center">{{ $trabalho->getQuantidadeAvaliacoes() }}</td>

                                        <td style="text-align:center">
                                            <a href="#" data-bs-toggle="modal"
                                                data-bs-target="#modalTrabalho{{ $trabalho->id }}">
                                                <img src="{{ asset('img/icons/documento.svg') }}" class="icon-card"
                                                    width="20" alt="atribuir">
                                            </a>
                                        </td>

                                        <td class="text-center">
                                            <span class="d-flex gap-3">
                                                <a href="{{ route('coord.trabalho.edit', ['id' => $trabalho->id]) }}">
                                                    <i class="bi bi-pencil-square text-primary fs-5"></i>
                                                </a>

                                                @if ($trabalho->status == 'rascunho')
                                                    <a
                                                        href="{{ route('trabalho.status', [$trabalho->id, 'arquivado']) }}">
                                                        <i class="bi bi-archive-fill text-secondary fs-5"></i>
                                                    </a>
                                                @elseif ($trabalho->status == 'arquivado')
                                                    <a href="#" data-bs-toggle="modal"
                                                        data-bs-target="#modalExcluirTrabalho_{{ $trabalho->id }}">
                                                        <i class="bi bi-trash text-danger fs-5"></i>
                                                    </a>
                                                @endif
                                            </span>
                                        </td>

                                    </tr>
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
                    @foreach ($areas as $area)
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
        $(document).ready(function() {
            if (id != null) {
                $('#modalTrabalho' + id).modal('show');
            }
        });
    </script>
@endsection
