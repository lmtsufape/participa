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
                                form="formEdidarPalestra{{ $palestra->id }}">Salvar</button>
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
        // Variáveis e funções globais que precisam ser acessadas pelo onclick do HTML
        let contadorNovosPalestrantes = 0;
        const placeholderUrl = "{{ asset('/img/nova_imagem.PNG') }}";

        function adicionarPalestrante(palestraId) {
            contadorNovosPalestrantes++;
            // Esta função permanece a mesma, pois já usa jQuery e funciona bem
            const containerSelector = `#palestrantesDeUmaPalestra${palestraId > 0 ? palestraId : ''}`;
            const novoIdSufixo = `novo_${contadorNovosPalestrantes}`;
            const isEdicao = palestraId > 0;
            const nomeInputFoto = isEdicao ? `fotoPalestrante[0][]` : `foto_palestrante[]`;

            const novoBlocoHtml = `
                <div class="palestrante-bloco" id="palestrantePalestra_${novoIdSufixo}">
                    <hr>
                    <div class="row">
                        <div class="col-11"><h5>Novo Palestrante</h5></div>
                        <div class="col-1 text-end">
                            <button type="button" class="btn btn-outline-danger btn-sm remover-palestrante" title="Remover palestrante">&times;</button>
                        </div>
                    </div>
                    ${isEdicao ? '<input type="hidden" name="idPalestrante[]" value="0">' : ''}
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
                                <input type="file" name="${nomeInputFoto}" id="foto_palestrante_${novoIdSufixo}" accept="image/jpeg, image/png">
                            </div>
                        </div>
                    </div>
                </div>`;
            $(containerSelector).append(novoBlocoHtml);
        }

        // Envolvemos toda a lógica de "listeners" no document.ready do jQuery
        $(document).ready(function() {

            // Listener de clique para remover, usando delegação de eventos do jQuery
            $(document).on('click', '.remover-palestrante', function() {
                $(this).closest('.palestrante-bloco').remove();
            });

            // Listener de clique para o loader da imagem
            $(document).on('click', '.imagem-loader', function() {
                $(this).next('div').find('input[type="file"]').click();
            });


            $(document).on('change', 'input[type="file"][name^="foto"]', function() {
                processarImagem(this);
            });

            $(document).on('submit', 'form[id^="formEdidarPalestra"], form[id="formNovaPalestra"]', function(event) {
                const form = this;
                let isFormValid = true;

                // ATUALIZADO: Verifica se existe pelo menos um palestrante
                if ($(form).find('.palestrante-bloco').length < 1) {
                    alert('É necessário adicionar pelo menos um palestrante.');
                    isFormValid = false;
                } else {
                    // Valida cada campo de texto individualmente
                    $(form).find('input[name="nomeDoPalestrante[]"], input[name="emailDoPalestrante[]"]').each(function() {
                        if (!validarCampoTexto(this)) {
                            isFormValid = false;
                        }
                    });
                }

                if (!isFormValid) {
                    event.preventDefault(); // Impede o envio do formulário
                    // Opcional: focar no primeiro campo com erro
                }
            });


            $(document).on('input', 'input[name="nomeDoPalestrante[]"], input[name="emailDoPalestrante[]"]', function() {
                validarCampoTexto(this);
            });

        });

        // --- Funções de Validação e Exibição de Erro ---
        function validarCampoTexto(input) {
            const $input = $(input);
            let ehValido = true;
            let mensagemErro = '';
            const valor = $input.val().trim();

            if ($input.attr('name') === 'nomeDoPalestrante[]') {
                if (valor === '') {
                    ehValido = false;
                    mensagemErro = 'O campo nome é obrigatório.';
                } else if (valor.length < 10) { // ATUALIZADO: Adicionada verificação de mínimo de 10 caracteres
                    ehValido = false;
                    mensagemErro = 'O nome deve ter no mínimo 10 caracteres.';
                }
            } else if ($input.attr('name') === 'emailDoPalestrante[]') {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (valor === '') {
                    ehValido = false;
                    mensagemErro = 'O campo e-mail é obrigatório.';
                } else if (!emailRegex.test(valor)) {
                    ehValido = false;
                    mensagemErro = 'Por favor, insira um endereço de e-mail válido.';
                }
            }

            if (!ehValido) {
                mostrarErroDeTexto($input, mensagemErro);
                return false;
            } else {
                limparErroDeTexto($input);
                return true;
            }
        }

        // --- FUNÇÃO DE PROCESSAR IMAGEM (ATUALIZADA) ---
        function processarImagem(input) {
            const parentCol = $(input).closest('.col-md-6');
            if (!parentCol.length) return;
            const preview = parentCol.find('.imagem-loader img')[0];

            const placeholderWidth = '150px';
            const finalPreviewWidth = '360px';
            const file = input.files[0];

            // Limpa qualquer erro anterior desta área
            limparErroDeImagem(parentCol);

            if (!file) {
                preview.src = placeholderUrl;
                preview.style.maxWidth = placeholderWidth;
                return;
            }

            if (!['image/jpeg', 'image/png'].includes(file.type)) {
                mostrarErroDeImagem(parentCol, 'Formato inválido. Use JPEG ou PNG.');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    // A CONDIÇÃO CORRETA PARA TAMANHO MÁXIMO
                    if (this.width > 760 || this.height > 360) {
                        mostrarErroDeImagem(parentCol, 'A imagem deve ter no máximo 760x360 pixels.');
                        input.value = ''; // Limpa o input de arquivo
                        preview.src = placeholderUrl;
                        preview.style.maxWidth = placeholderWidth;
                    } else {
                        // Se o tamanho for válido, mostra o preview
                        preview.src = e.target.result;
                        preview.style.maxWidth = finalPreviewWidth;
                    }
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        function mostrarErroDeImagem(container, mensagem) {
            limparErroDeImagem(container); // Garante que não haja erros duplicados
            let errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback d-block js-error';
            errorDiv.textContent = mensagem;
            // Adiciona a div de erro após o container da imagem
            $(container).find('.imagem-loader').parent().append(errorDiv);
        }

        function limparErroDeImagem(container) {
            if (container) {
                const errorDiv = container.find('.js-error');
                if (errorDiv.length) {
                    errorDiv.remove();
                }
            }
        }

        function mostrarErroDeTexto($input, mensagem) {
            limparErroDeTexto($input); // Remove erros antigos
            $input.addClass('is-invalid');
            const $errorSpan = $('<span></span>')
                .addClass('invalid-feedback')
                .attr('role', 'alert')
                .html(`<strong>${mensagem}</strong>`);
            $input.after($errorSpan);
        }

        function limparErroDeTexto($input) {
            $input.removeClass('is-invalid');
            if ($input.next().hasClass('invalid-feedback')) {
                $input.next().remove();
            }
        }


        // Funções antigas que você ainda precisa
        function editarPalestra(id) {
            $(`#formEdidarPalestra${id}`).submit();
        }
        function removerPalestrantePalestra(id) {
            $(`#palestrantePalestra${id}`).remove();
        }

    </script>
@endsection
