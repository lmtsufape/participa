@extends('coordenador.detalhesEvento')

@section('menu')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <!-- Modal para editar a palestra-->
    @foreach ($palestras as $palestra)
        <div class="modal fade bd-example-modal-lg"
            id="modalPalestraEdit{{ $palestra->id }}"
            tabindex="-1"
            role="dialog"
            aria-labelledby="modalLabelPalestraEdit{{ $palestra->id }}"
            aria-hidden="true">
            <div class="modal-dialog modal-lg"
                role="document">
                <div class="modal-content">
                    <div class="modal-header"
                        style="background-color: #114048ff; color: white;">
                        <h5 class="modal-title"
                            id="modalLabelPalestraEdit{{ $palestra->id }}">Editar Palestra</h5>
                        <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                            style="color: white;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formEdidarPalestra{{ $palestra->id }}"
                            method="POST"
                            action="{{ route('coord.palestrantes.update', ['id' => $palestra->id]) }}">
                            @csrf
                            @method('PUT')
                            <div class="container">
                                <div class="row form-group">
                                    <input type="hidden"
                                        name="idPalestra"
                                        value="{{ $palestra->id }}">
                                    <input type="hidden"
                                        name="eventoId"
                                        value="{{ $evento->id }}">
                                    <div class="col-sm-6">
                                        <label for="titulo">Titulo*:</label>
                                        <input class="form-control @error('titulo') is-invalid @enderror"
                                            type="text"
                                            name="titulo"
                                            id="titulo{{ $palestra->id }}"
                                            value="@if (old('titulo') != null) {{ old('titulo') }} @else {{ $palestra->titulo }} @endif"
                                            placeholder="Nova palestra">

                                        @error('titulo')
                                            <span class="invalid-feedback"
                                                role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <hr>
                                <div id="palestrantesDeUmaPalestra{{ $palestra->id }}">
                                    @foreach ($palestra->palestrantes as $palestrante)
                                        <div id="palestrantePalestra{{ $palestrante->id }}"
                                            class="row form-group">
                                            <input type="hidden"
                                                name="idPalestrante[]"
                                                id="palestrante-{{ $palestrante->id }}"
                                                value="{{ $palestrante->id }}">
                                            <div class="container">
                                                <h5>Palestrante</h5>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label for="nome">Nome:</label>
                                                        <input class="form-control"
                                                            type="text"
                                                            name="nomeDoPalestrante[]"
                                                            id="nome{{ $palestra->id }}{{ $palestrante->id }}"
                                                            value="@if (old('nomePalestrante[]') != null) {{ old('nomePalestrante[]') }}@else{{ $palestrante->nome }} @endif"
                                                            placeholder="Nome do palestrante">
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <label for="email">E-mail:</label>
                                                        <input class="form-control"
                                                            type="text"
                                                            name="emailDoPalestrante[]"
                                                            id="email{{ $palestra->id }}{{ $palestrante->id }}"
                                                            value="@if (old('emailDoPalestrante[]') != null) {{ old('emailDoPalestrante[]') }}@else{{ $palestrante->email }} @endif"
                                                            placeholder="E-mail do palestrante">
                                                    </div>
                                                    <div class='d-flex justify-content-end col-sm-1'>
                                                        <button type='button' onclick='removerPalestrantePalestra({{$palestrante->id}})' style='border:none; background-color: rgba(0,0,0,0);'><img src='{{ asset('/img/icons/user-times-solid.svg') }}' width='30px' height='auto'  alt='remover convidade' style='padding-top: 28px;'></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button id="buttonNovoPalestrante{{ $palestra->id }}"
                                    class="btn btn-primary"
                                    type="button"
                                    onclick="adicionarPalestrante({{ $palestra->id }})">+Adicionar palestrante</button>
                            </div>
                    </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">Fechar</button>
                        <button type="submit"
                            class="btn btn-primary"
                            onclick="editarPalestra({{ $palestra->id }})">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="modal fade bd-example-modal-lg"
        id="modalCriarPalestra"
        tabindex="-1"
        role="dialog"
        aria-labelledby="modalLabelCriarPalestra"
        aria-hidden="true">
        <div class="modal-dialog modal-lg"
            role="document">
            <div class="modal-content">
                <div class="modal-header"
                    style="background-color: #114048ff; color: white;">
                    <div class="container">
                        <h5 class="modal-title"
                            id="modalLabelCriarPalestra">Criar Palestra</h5>
                        <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                            style="color: white;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <form id="formNovaPalestra"
                        method="POST"
                        action="{{ route('coord.palestrantes.store') }}">
                        @csrf
                        <div class="container">
                            <div class="row form-group">
                                <input type="hidden"
                                    name="eventoId"
                                    value="{{ $evento->id }}">
                                <div class="col-sm-6">
                                    <label for="titulo">Titulo*:</label>
                                    <input class="form-control apenasLetras @error('título') is-invalid @enderror"
                                        type="text"
                                        name="titulo"
                                        id="titulo"
                                        value="{{ old('titulo') }}"
                                        placeholder="Nova palestra"
                                        required>

                                    @error('título')
                                        <span class="invalid-feedback"
                                            role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <hr>
                            <button id="buttonFecharDadosAdicionais"
                                type="button"
                                class="btn btn-primary"
                                style="background-color: white; color: rgb(41, 109, 211); border-color: rgb(50, 132, 255); @if (old('vagas') != null || old('valor') != null || old('carga_horaria') != null || old('palavrasChaves') != null || old('nomePalestrante') != null || old('emailPalestrante') != null) display: block; @else display: none; @endif"
                                onclick="fecharDadosAdicionais(0)">-Fechar dados opcionais</button>
                            <div id="palestrantesDeUmaPalestra">
                                <div class="row form-group">
                                    <div class="container">
                                        <h5>Palestrante</h5>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label for="nome">Nome:</label>
                                                <input
                                                    class="form-control apenasLetras @error('nomeDoPalestrante[]') is-invalid @enderror"
                                                    type="text"
                                                    name="nomeDoPalestrante[]"
                                                    id="nome"
                                                    value="{{ old('nomeDoPalestrante[]') }}"
                                                    placeholder="Nome do palestrante">

                                                @error('nomeDoPalestrante[]')
                                                    <span class="invalid-feedback"
                                                        role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="email">E-mail:</label>
                                                <input
                                                    class="form-control @error('emailDoPalestrante[]') is-invalid @enderror"
                                                    type="email"
                                                    name="emailDoPalestrante[]"
                                                    id="email"
                                                    value="{{ old('emailDoPalestrante[]') }}"
                                                    placeholder="E-mail do palestrante">

                                                @error('emailDoPalestrante[]')
                                                    <span class="invalid-feedback"
                                                        role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button id="buttonNovoPalestrante"
                                class="btn btn-primary"
                                type="button"
                                onclick="adicionarPalestrante(0)">+Adicionar palestrante</button>
                        </div>
                        <div class="modal-footer">
                            <button type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal">Fechar</button>
                            <button id="submitNovaPalestra"
                                type="submit"
                                class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    @foreach ($palestras as $palestra)
        <!-- Modal de exclusão -->
        <div class="modal fade"
            id="modalExcluirPalestra{{ $palestra->id }}"
            tabindex="-1"
            role="dialog"
            aria-labelledby="modalExcluirPalestraLabel{{ $palestra->id }}"
            aria-hidden="true">
            <div class="modal-dialog"
                role="document">
                <div class="modal-content">
                    <div class="modal-header"
                        style="background-color: #114048ff; color: white;">
                        <h5 class="modal-title"
                            id="modalExcluirPalestraLabel{{ $palestra->id }}">Confirmar</h5>
                        <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                            style="color: white;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Tem certeza que deseja excluir?
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">Cancelar</button>
                        <form method="POST"
                            action="{{ route('coord.palestrantes.destroy', $palestra->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="btn btn-primary">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div id="divListarComissao"
        class="comissao"
        style="display: block">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Palestrantes</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Palestras</h5>
                        <small>Clique em uma palestra para editar</small>
                        <p class="card-text">
                        <table class="table table-hover table-responsive-lg table-sm mt-3">
                            <thead>
                                <th>
                                <th>Titulo</th>
                                <th>Excluir</th>
                                </th>
                            </thead>
                            @foreach ($palestras as $palestra)
                                <tbody>
                                    <th>
                                    <td data-toggle="modal"
                                        data-target="#modalPalestraEdit{{ $palestra->id }}">{{ $palestra->titulo }}</td>
                                    <td data-toggle="modal"
                                        data-target="#modalExcluirPalestra{{ $palestra->id }}"><button
                                            style="border: none; background-color: rgba(255, 255, 255, 0);"><img
                                                src="{{ asset('img/icons/trash-alt-regular.svg') }}"
                                                class="icon-card"
                                                alt=""></button></td>
                                    </th>
                                </tbody>
                            @endforeach
                        </table>
                        </p>
                    </div>
                </div>
            </div>
        </div>


    </div>{{-- End Listar palestras --}}
@endsection

@section('javascript')
    @parent
    <script>
        var contadorPalestrantes = 1;

        function adicionarPalestrante(id) {
            contadorPalestrantes++;
            if (id == 0) {
                $('#palestrantesDeUmaPalestra').append(
                    "<div id='novoPalestrantePalestra" + contadorPalestrantes + "' class='row form-group'>" +
                    "<div class='container'>" +
                    "<h5>Palestrante</h5>" +
                    "<div class='row'>" +
                    "<div class='col-sm-6'>" +
                    "<label for='nome'>Nome:</label>" +
                    "<input class='form-control apenasLetras' type='text' name='nomeDoPalestrante[]' id='nome'  value='{{ old('nomePalestrante') }}' placeholder='Nome do palestrante'>" +
                    "</div>" +
                    "<div class='col-sm-5'>" +
                    "<label for='email'>E-mail:</label>" +
                    "<input class='form-control' type='email' name='emailDoPalestrante[]' id='email' value='{{ old('emailPalestrante') }}' placeholder='E-mail do palestrante'>" +
                    "</div>" +
                    "<div class='d-flex justify-content-end col-sm-1'>" +
                    "<button type='button' onclick='removerPalestranteNovaPalestra(" + contadorPalestrantes +
                    ")' style='border:none; background-color: rgba(0,0,0,0);'><img src='{{ asset('/img/icons/user-times-solid.svg') }}' width='30px' height='auto'  alt='remover convidade' style='padding-top: 28px;'></button>" +
                    "</div>" +
                    "</div>" +
                    "</div>" +
                    "</div>"
                )
            } else if (id > 0) {
                $('#palestrantesDeUmaPalestra' + id).append(
                    "<div id='novoPalestrantePalestra" + contadorPalestrantes + "' class='row form-group'>" +
                    "<div class='container'>" +
                    "<h5>Palestrante</h5>" +
                    "<div class='row'>" +
                    "<input type='hidden' name='idPalestrante[]' value='0'>" +
                    "<div class='col-sm-6'>" +
                    "<label for='nome'>Nome:</label>" +
                    "<input class='form-control apenasLetras' type='text' name='nomeDoPalestrante[]' id='nome'  value='{{ old('nomePalestrante') }}' placeholder='Nome do palestrante'>" +
                    "</div>" +
                    "<div class='col-sm-5'>" +
                    "<label for='email'>E-mail:</label>" +
                    "<input class='form-control' type='email' name='emailDoPalestrante[]' id='email' value='{{ old('emailPalestrante') }}' placeholder='E-mail do palestrante'>" +
                    "</div>" +
                    "<div class='d-flex justify-content-end col-sm-1'>" +
                    "<button type='button' onclick='removerPalestranteNovaPalestra(" + contadorPalestrantes +
                    ")' style='border:none; background-color: rgba(0,0,0,0);'><img src='{{ asset('/img/icons/user-times-solid.svg') }}' width='30px' height='auto'  alt='remover convidade' style='padding-top: 28px;'></button>" +
                    "</div>" +
                    "</div>" +
                    "</div>" +
                    "</div>"
                )
            }
        }

        //Função que remove o palestrante
        function removerPalestranteNovaPalestra(id) {
            contadorPalestrantes--;
            $("#novoPalestrantePalestra" + id).remove();
        }

        //Função que subemete o form de edição de uma palestra
        function editarPalestra(id) {
            var form = document.getElementById('formEdidarPalestra' + id);
            form.submit();
        }

        //Remover palestrante existente de editar palestra
        function removerPalestrantePalestra(id) {
            $("#palestrantePalestra" + id).remove();
        }
    </script>
@endsection
