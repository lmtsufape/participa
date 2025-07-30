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
                    </div>
                    <div class="modal-body">
                        <form id="formEdidarPalestra{{ $palestra->id }}"
                              method="POST"
                              action="{{ route('coord.palestrantes.update', ['id' => $palestra->id]) }}"
                              enctype="multipart/form-data">
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
                                        <div class="palestrante-bloco" id="palestrantePalestra{{ $palestrante->id }}">
                                            <hr>
                                            <div class="row">
                                                <div class="col-11"><h5>Palestrante</h5></div>
                                                <div class="col-1 text-end">
                                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removerPalestrantePalestra({{ $palestrante->id }})">&times;</button>
                                                </div>
                                            </div>

                                            <input type="hidden" name="idPalestrante[]" value="{{ $palestrante->id }}">

                                            <div class="row">
                                                <div class="col-12 col-md-6 mb-3">
                                                    <label for="nomeDoPalestrante_{{ $palestrante->id }}">Nome:</label>
                                                    <input class="form-control" type="text" name="nomeDoPalestrante[]" id="nomeDoPalestrante_{{ $palestrante->id }}" value="{{ $palestrante->nome }}" placeholder="Nome do palestrante" required>
                                                </div>
                                                <div class="col-12 col-md-6 mb-3">
                                                    <label for="emailDoPalestrante_{{ $palestrante->id }}">E-mail:</label>
                                                    <input class="form-control" type="email" name="emailDoPalestrante[]" id="emailDoPalestrante_{{ $palestrante->id }}" value="{{ $palestrante->email }}" placeholder="E-mail do palestrante" required>
                                                </div>

                                                <div class="col-12 col-md-6 mb-3">
                                                    <label class="fw-bold mb-1 d-block">Foto (Opcional - 760x360px):</label>
                                                    <div class="imagem-loader" style="display: inline-block; vertical-align: top;">
                                                        @if ($palestrante->fotoPalestrante)
                                                            <img id="preview_{{ $palestrante->id }}" class="img-fluid" src="{{ asset('storage/' . $palestrante->fotoPalestrante) }}" alt="Foto atual" style="max-width: 360px;">
                                                        @else
                                                            <img id="preview_{{ $palestrante->id }}" class="img-fluid" src="{{ asset('/img/nova_imagem.PNG') }}" alt="Clique para enviar uma imagem" style="max-width: 150px;">
                                                        @endif
                                                    </div>
                                                    <div style="display: none;">
                                                        <input type="file" name="fotoPalestrante[{{ $palestrante->id }}]" id="foto_palestrante_{{ $palestrante->id }}" accept="image/jpeg, image/png">
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
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
                    </div>
                    <div class="modal-body">
                        Tem certeza que deseja excluir?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
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
                        <div class="row">
                            <div class="col">
                                <small>Clique em uma palestra para editar</small>
                                <h6 class="card-subtitle mb-2 text-muted">Obs.: ao exportar o arquivo csv, usar o delimitador , (vírgula) para abrir o arquivo</h6>
                            </div>
                            <div class="col justify-content-end d-flex">
                                <form action="{{route('coord.palestrantes.exportar', [$evento])}}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary float-md-right">Exportar .csv</button>
                                </form>
                            </div>

                        </div>
                        <p class="card-text">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm mt-3">
                                <thead>
                                    <th>
                                    <th>Título</th>
                                    <th>Excluir</th>
                                    </th>
                                </thead>
                                @foreach ($palestras as $palestra)
                                    <tbody>
                                        <th>
                                        <td data-bs-toggle="modal"
                                            data-bs-target="#modalPalestraEdit{{ $palestra->id }}">{{ $palestra->titulo }}</td>
                                        <td>
                                            <button type="button" class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#modalExcluirPalestra{{ $palestra->id }}">
                                                <img src="{{ asset('img/icons/lixo.png') }}" width="20" alt="Excluir">
                                            </button>
                                        </td>
                                        </th>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                        </p>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection
@section('javascript')
    @parent
    <style>
        /* Seus estilos .imagem-loader permanecem os mesmos */
        .imagem-loader {
            cursor: pointer;
            border: 2px dashed #ccc;
            padding: 5px;
            border-radius: 5px;
            transition: border-color 0.3s;
            display: inline-block;
            vertical-align: top;
        }
        .imagem-loader:hover {
            border-color: #007bff;
        }
    </style>

    <script>

        let contadorNovosPalestrantes = {{ count($palestra->palestrantes ?? []) }}; // Inicia o contador corretamente
        const placeholderUrl = "{{ asset('/img/nova_imagem.PNG') }}";

        function adicionarPalestrante(palestraId) {
            contadorNovosPalestrantes++;
            const containerSelector = `#palestrantesDeUmaPalestra${palestraId}`;
            const novoIdSufixo = `novo_${contadorNovosPalestrantes}`;

            const novoBlocoHtml = `
                <div class="palestrante-bloco" id="palestrantePalestra_${novoIdSufixo}">
                    <hr>
                    <div class="row">
                        <div class="col-11"><h5>Novo Palestrante</h5></div>
                        <div class="col-1 text-end">
                            <button type="button" class="btn btn-outline-danger btn-sm remover-palestrante" title="Remover palestrante">&times;</button>
                        </div>
                    </div>
                    <input type="hidden" name="idPalestrante[]" value="0">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label class="required-field" for="nomeDoPalestrante_${novoIdSufixo}">Nome:</label>
                            <input class="form-control" type="text" name="nomeDoPalestrante[]" id="nomeDoPalestrante_${novoIdSufixo}" placeholder="Nome do palestrante" required>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label class="required-field" for="emailDoPalestrante_${novoIdSufixo}">E-mail:</label>
                            <input class="form-control" type="email" name="emailDoPalestrante[]" id="emailDoPalestrante_${novoIdSufixo}" placeholder="E-mail do palestrante" required>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label class="fw-bold mb-1 d-block">Foto (Opcional - 760x360px):</label>
                            <div class="imagem-loader">
                                <img id="preview_${novoIdSufixo}" class="img-fluid" src="${placeholderUrl}" alt="Clique para enviar uma imagem" style="max-width: 150px;">
                            </div>
                            <div style="display: none;">
                                <input type="file" name="fotoPalestrante[0][]" id="foto_palestrante_${novoIdSufixo}" accept="image/jpeg, image/png">
                            </div>
                        </div>
                    </div>
                </div>`;
            $(containerSelector).append(novoBlocoHtml);
        }


        document.addEventListener('DOMContentLoaded', function() {

            document.body.addEventListener('click', function(event) {

                const loader = event.target.closest('.imagem-loader');
                if (loader) {

                    loader.nextElementSibling.querySelector('input[type="file"]').click();
                    return;
                }


                const removeButton = event.target.closest('.remover-palestrante');
                if (removeButton) {
                    removeButton.closest('.palestrante-bloco').remove();
                }
            });


            document.body.addEventListener('change', function(event) {
                if (event.target.matches('input[type="file"][name^="fotoPalestrante"]')) {
                    processarImagem(event.target);
                }
            });
        });


        function processarImagem(input) {
            const parentCol = input.closest('.col-md-6');
            if (!parentCol) return; // Sai se não encontrar o container
            const preview = parentCol.querySelector('.imagem-loader img');

            const placeholderWidth = '150px';
            const finalPreviewWidth = '360px';

            const file = input.files[0];
            limparErro(parentCol);

            if (!file) {
                preview.src = placeholderUrl;
                preview.style.maxWidth = placeholderWidth;
                return;
            }

            if (!['image/jpeg', 'image/png'].includes(file.type)) {
                mostrarErro(parentCol, 'Formato inválido. Use JPEG ou PNG.');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    if (this.width !== 760 || this.height !== 360) {
                        mostrarErro(parentCol, 'A imagem deve ter exatamente 760x360 pixels.');
                        input.value = '';
                        preview.src = placeholderUrl;
                        preview.style.maxWidth = placeholderWidth;
                    } else {
                        preview.src = e.target.result;
                        preview.style.maxWidth = finalPreviewWidth;
                    }
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        function mostrarErro(container, mensagem) {
            limparErro(container);
            let errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback d-block js-error';
            errorDiv.textContent = mensagem;
            container.querySelector('input[type="file"]').parentElement.insertAdjacentElement('afterend', errorDiv);
        }

        function limparErro(container) {
            if (container) {
                const errorDiv = container.querySelector('.js-error');
                if (errorDiv) errorDiv.remove();
            }
        }

        // Funções que você já tinha para os modais
        function editarPalestra(id) {
            document.getElementById('formEdidarPalestra' + id).submit();
        }

        function removerPalestrantePalestra(id) {
            $(`#palestrantePalestra${id}`).remove();
        }
    </script>
@endsection
