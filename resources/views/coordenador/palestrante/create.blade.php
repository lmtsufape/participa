@extends('coordenador.detalhesEvento')

@section('menu')
    <style>
        /* ... */
        .imagem-loader {
            cursor: pointer;
            border: 2px dashed #ccc;
            padding: 5px;
            border-radius: 5px;
            transition: border-color 0.3s;
            display: inline-block;
            vertical-align: top;
            align-items: center;
            justify-content: center;
        }
        .imagem-loader:hover {
            border-color: #007bff;
        }
    </style>

    <div id="divCadastrarPalestra" class="comissao" style="display: block">
        <div class="row">
            <div class="col-12">
                <h1 class="titulo-detalhes">Cadastrar Palestras</h1>
                <h6 class="card-subtitle mb-2 text-muted">Cadastre uma nova palestra para certificação</h6>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12">
                <form id="formNovaPalestra"
                      method="POST"
                      action="{{ route('coord.palestrantes.store') }}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="container-fluid">
                        <div class="row form-group">
                            <input type="hidden" name="eventoId" value="{{ $evento->id }}">
                            <div class="col-12 col-md-6">
                                <label class="required-field" for="titulo">Título:</label>
                                <input class="form-control @error('titulo') is-invalid @enderror"
                                       type="text" name="titulo" id="titulo" value="{{ old('titulo') }}"
                                       placeholder="Nova palestra" required>
                                @error('titulo')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <hr>


                        <div id="palestrantes-container">
                            <div class="palestrante-bloco" id="bloco_palestrante_1">
                                <div class="row">
                                    <div class="col-12"><h5>Palestrante 1</h5></div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-6 mb-3">
                                        <label class="required-field" for="nomeDoPalestrante_1">Nome:</label>
                                        <input class="form-control" type="text" name="nomeDoPalestrante[]" id="nomeDoPalestrante_1" placeholder="Nome do palestrante" required>
                                    </div>
                                    <div class="col-12 col-md-6 mb-3">
                                        <label class="required-field" for="emailDoPalestrante_1">E-mail:</label>
                                        <input class="form-control" type="email" name="emailDoPalestrante[]" id="emailDoPalestrante_1" placeholder="E-mail do palestrante" required>
                                    </div>
                                    <div class="col-12 col-md-6 mb-3">
                                        <label for="fotoPalestrante_1" class="fw-bold mb-1 d-block">Foto (Opcional - 760x360px):</label>
                                        <div class="imagem-loader">
                                            <img id="preview_1" class="img-fluid" src="{{ asset('/img/nova_imagem.PNG') }}" alt="Clique para enviar uma imagem" style="max-width: 360px;", >
                                        </div>
                                        <div style="display: none;">
                                            <input type="file" name="fotoPalestrante[]" id="fotoPalestrante_1" accept="image/jpeg, image/png">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <button id="buttonNovoPalestrante" class="btn btn-primary mb-3" type="button">+Adicionar palestrante</button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary me-2" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    @parent
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('palestrantes-container');
            let contadorPalestrantes = 1;

            document.getElementById('buttonNovoPalestrante').addEventListener('click', function() {
                contadorPalestrantes++;
                const novoBloco = document.createElement('div');
                novoBloco.className = 'palestrante-bloco mt-4 pt-4 border-top';
                novoBloco.id = `bloco_palestrante_${contadorPalestrantes}`;

                novoBloco.innerHTML = `
                    <div class="row">
                        <div class="col-11"><h5>Palestrante ${contadorPalestrantes}</h5></div>
                        <div class="col-1 text-end">
                            <button type="button" class="btn btn-outline-danger btn-sm remover-palestrante" title="Remover palestrante">&times;</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label class="required-field" for="nomeDoPalestrante_${contadorPalestrantes}">Nome:</label>
                            <input class="form-control" type="text" name="nomeDoPalestrante[]" id="nomeDoPalestrante_${contadorPalestrantes}" placeholder="Nome do palestrante" required>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label class="required-field" for="emailDoPalestrante_${contadorPalestrantes}">E-mail:</label>
                            <input class="form-control" type="email" name="emailDoPalestrante[]" id="emailDoPalestrante_${contadorPalestrantes}" placeholder="E-mail do palestrante" required>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label for="fotoPalestrante_${contadorPalestrantes}" class="fw-bold mb-1 d-block">Foto (Opcional - 760x360px):</label>
                            <div class="imagem-loader">
                                <img id="preview_${contadorPalestrantes}" class="img-fluid" src="{{ asset('/img/nova_imagem.PNG') }}" alt="Clique para enviar uma imagem" style="max-width: 360px">
                            </div>
                            <div style="display: none;">
                                <input type="file" name="fotoPalestrante[]" id="fotoPalestrante_${contadorPalestrantes}" accept="image/jpeg, image/png">
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(novoBloco);
            });


            container.addEventListener('click', function(event) {

                const loader = event.target.closest('.imagem-loader');
                if (loader) {
                    const inputWrapper = loader.nextElementSibling;
                    const fileInput = inputWrapper.querySelector('input[type="file"]');
                    if (fileInput) {
                        fileInput.click();
                    }
                }

                // Se o clique foi no botão de remover
                if (event.target.classList.contains('remover-palestrante')) {
                    event.target.closest('.palestrante-bloco').remove();
                }
            });

            container.addEventListener('change', function(event) {

                if (event.target.matches('input[name="fotoPalestrante[]"]')) {
                    processarImagem(event.target);
                }
            });


            function processarImagem(input) {
                const idNumero = input.id.split('_').pop();
                const preview = document.getElementById(`preview_${idNumero}`);
                const defaultSrc = "{{ asset('/img/nova_imagem.PNG') }}";
                const parentCol = input.closest('.col-md-6');

                const file = input.files[0];
                limparErro(parentCol);

                if (!file) {
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
                            preview.src = defaultSrc;
                        } else {

                            preview.src = e.target.result;
                        }
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }

            function mostrarErro(container, mensagem) {
                let errorDiv = container.querySelector('.js-error');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback d-block js-error';
                    container.appendChild(errorDiv);
                }
                errorDiv.textContent = mensagem;
            }

            function limparErro(container) {
                const errorDiv = container.querySelector('.js-error');
                if (errorDiv) {
                    errorDiv.remove();
                }
            }
        });
    </script>
@endsection
