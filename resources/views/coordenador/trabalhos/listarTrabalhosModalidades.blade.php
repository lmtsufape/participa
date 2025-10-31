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

        <div class="row mt-5">
            <div class="col-md-1">
                <div class="btn-group mb-2" role="group" aria-label="Button group with nested dropdown">
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Opções
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item"
                                href="{{ route('coord.listarTrabalhos', ['eventoId' => $evento->id, 'modalidadeId' => $modalidade->id, 'filter[status]' => 'rascunho']) }}">
                                Todos
                            </a>
                            <a class="dropdown-item"
                                href="{{ route('coord.listarTrabalhos', ['eventoId' => $evento->id, 'modalidadeId' => $modalidade->id, 'filter[status]' => 'arquivado']) }}">
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
            </div>
            <div class="col-md-11">
                <form method="GET" class="mb-3">
                    <div class="input-group">
                    <input type="search" name="filter[q]" value="{{ request('filter.q') }}"
                        class="form-control" placeholder="Buscar por ID, título ou autor...">
                    <input type="hidden" name="eventoId" id="eventoId" value="{{ $evento->id }}">
                    <input type="hidden" name="modalidadeId" id="modalidadeId" value="{{ $modalidade->id }}">
                    <button class="btn btn-outline-primary" type="submit" aria-label="Buscar trabalhos">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <span class="ms-1">Buscar</span>
                    </button>

                    </div>
                </form>
            </div>
        </div>

        {{-- Tabela Trabalhos --}}
        <div class="row table-trabalhos">
            <div class="col-sm-12">

                <form action="{{ route('atribuicao.check') }}" method="post">
                    @csrf

                    <input type="hidden" name="eventoId" value="{{ $evento->id }}">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-hover ">
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
                                                <livewire:trabalhos.buttons.atribuir-trabalho
                                                    :trabalho-id="$trabalho->id"
                                                    :evento-id="$evento->id"
                                                />
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
                    </div>
                </form>
            </div>

        </div>

    </div>

    @foreach ($trabalhos as $trabalho)
        <!-- Modal Trabalho -->
        <x-modal-excluir-trabalho :trabalho="$trabalho" />
    @endforeach
@endsection

@section('javascript')
    @parent
    <script>
        function marcarCheckboxes() {
            $(".trabalhos").prop('checked', $('#selectAllCheckboxes').is(":checked"));
        }
    </script>
@endsection
