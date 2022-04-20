@extends('coordenador.detalhesEvento')

@section('menu')
    <div id="divListarTrabalhos"
        style="display: block">
        <div class="row ">
            <div class="col-sm-12">
                <h2 class="">Emissões do certificado {{ $certificado->nome }}</h2>
            </div>
            {{-- <div class="col-sm-3"></div> --}}
            {{-- <div class="col-sm-3"> --}}
            {{-- <form method="GET"
                    action="{{ route('distribuicao') }}">
                    <input type="hidden"
                        name="eventoId"
                        value="{{ $evento->id }}">
                    <button onclick="event.preventDefault();"
                        data-toggle="modal"
                        data-target="#modalDistribuicaoAutomatica"
                        class="btn btn-primary"
                        style="width:100%">
                        {{ __('Distribuir trabalhos') }}
                    </button>
                </form> --}}
        </div>
    </div>
    <div class="row table-trabalhos">
        <div class="col-sm-12">
            <form action="{{ route('atribuicao.check') }}"
                method="post">
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
                {{-- <div class="btn-group mb-2"
                        role="group"
                        aria-label="Button group with nested dropdown">
                        <div class="btn-group"
                            role="group">
                            <button id="btnGroupDrop1"
                                type="button"
                                class="btn btn-secondary dropdown-toggle"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                                Opções
                            </button>
                            <div class="dropdown-menu"
                                aria-labelledby="btnGroupDrop1">
                                <a class="dropdown-item"
                                    href="{{ route('coord.listarTrabalhosModalidades', ['eventoId' => $evento->id,'modalidadeId' => $modalidade->id,'titulo','asc','rascunho']) }}">
                                    Todos
                                </a>
                                <a class="dropdown-item"
                                    href="{{ route('coord.listarTrabalhosModalidades', ['eventoId' => $evento->id,'modalidadeId' => $modalidade->id,'titulo','asc','arquivado']) }}">
                                    Arquivados
                                </a>
                                <a class="dropdown-item disabled"
                                    href="#">
                                    Submetidos
                                </a>
                                <a class="dropdown-item disabled"
                                    href="#">
                                    Aprovados
                                </a>
                                <a class="dropdown-item disabled"
                                    href="#">
                                    Corrigidos
                                </a>
                                <a class="dropdown-item disabled"
                                    href="#">
                                    Rascunhos
                                </a>
                            </div>
                        </div>
                    </div>
                    <input type="hidden"
                        name="eventoId"
                        value="{{ $evento->id }}"> --}}
                <br>
                @if ($usuarios->count() == 0)
                    <h5>Nenhum certificado emitido</h5>
                @else
                    <table class="table table-hover table-responsive-lg table-sm table-striped">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <input type="checkbox"
                                        id="selectAllCheckboxes"
                                        onclick="marcarCheckboxes()">
                                    <label for="selectAllCheckboxes"
                                        style="margin-bottom: 0px;">Selecionar</label>
                                </th>
                                <th scope="col">
                                    Nome
                                </th>
                                <th scope="col">
                                    E-mail
                                </th>
                                <th scope="col">
                                    @switch($certificado->tipo)
                                        @case($tipos['apresentador'])
                                            Trabalho
                                        @break

                                        @case($tipos['expositor'])
                                            Palestra
                                        @break

                                        @case($tipos['outras_comissoes'])
                                            Comissão
                                        @break

                                        @default
                                        @break
                                    @endswitch
                                </th>
                                <th scope="col">
                                    Data
                                </th>
                                <th scope="col"
                                    style="text-align:center">
                                    Invalidar
                                </th>
                                <th scope="col"
                                    style="text-align:center">
                                    Visualizar
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($usuarios as $i => $usuario)
                                <tr>
                                    <td style="text-align:center">
                                        <input type="checkbox"
                                            aria-label="Checkbox for following text input"
                                            name="id[]"
                                            value="{{ $usuario->id }}"
                                            class="trabalhos">
                                    </td>
                                    <td>
                                        {{ $usuario->name }}
                                    </td>
                                    <td>
                                        {{ $usuario->email }}

                                    </td>
                                    <td>
                                        @switch($certificado->tipo)
                                            @case($tipos['apresentador'])
                                                {{ $trabalhos->find($usuario->pivot->trabalho_id)->titulo }}
                                            @break

                                            @case($tipos['expositor'])
                                                {{ $palestras->find($usuario->pivot->palestra_id)->nome }}
                                            @break

                                            @case($tipos['outras_comissoes'])
                                                {{ $comissao->nome }}
                                            @break

                                            @default
                                        @endswitch
                                    </td>
                                    <td>
                                        {{ date('d/m/Y H:i:s', strtotime($usuario->pivot->created_at)) }}
                                    </td>
                                    <td>
                                        <a href="#"
                                            class="text-reset d-flex justify-content-center">
                                            <i class="fas fa-archive"></i>
                                        </a>
                                    </td>
                                    <td>
                                        @switch($certificado->tipo)
                                            @case($tipos['apresentador'])
                                                <a class="text-reset d-flex justify-content-center" href="{{ route('coord.previewCertificado', [$certificado->id, $usuario->id, $usuario->pivot->trabalho_id]) }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer">
                                                    <i class="far fa-eye"></i>
                                                </a>
                                            @break

                                            @case($tipos['expositor'])
                                                <a class="text-reset d-flex justify-content-center" href="{{ route('coord.previewCertificado', [$certificado->id, $usuario->id, $usuario->pivot->palestra_id]) }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer">
                                                    <i class="far fa-eye"></i>
                                                </a>
                                            @break

                                            @case($tipos['outras_comissoes'])
                                                <a class="text-reset d-flex justify-content-center" href="{{ route('coord.previewCertificado', [$certificado->id, $usuario->id, $usuario->pivot->comissao_id]) }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer">
                                                    <i class="far fa-eye"></i>
                                                </a>
                                            @break

                                            @default
                                                <a class="text-reset d-flex justify-content-center" href="{{ route('coord.previewCertificado', [$certificado->id, $usuario->id, 0]) }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer">
                                                    <i class="far fa-eye"></i>
                                                </a>
                                        @endswitch
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
        </form>
    </div>
</div>
</div>
@endsection

@section('javascript')
@parent
<script>
    function marcarCheckboxes() {
        $(".trabalhos").prop('checked', $('#selectAllCheckboxes').is(":checked"));
    }
</script>
@endsection
