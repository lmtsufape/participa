@extends('coordenador.detalhesEvento')

@section('menu')
    <div id="divCadastrarAssinatura" class="comissao">
        <div class="row">
            <div class="col-12">
                <h1 class="titulo-detalhes">Cadastrar Assinatura</h1>
                <h6 class="card-subtitle mb-2 text-muted">Cadastre uma nova assinatura para certificados</h6>
            </div>
        </div>
    </div>

    <br>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Nova Assinatura</h5>
                </div>
                <div class="card-body">
                    <form id="formCadastrarAssinatura" action="{{route('coord.assinatura.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="eventoId" value="{{$evento->id}}">

                        <!-- Campos de texto -->
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="nome" class="form-label required-field">{{ __('Nome') }}</label>
                                <input id="nome"
                                       class="form-control @error('nome') is-invalid @enderror"
                                       type="text"
                                       name="nome"
                                       value="{{old('nome')}}"
                                       placeholder="Digite o nome completo"
                                       required
                                       autofocus
                                       autocomplete="nome">

                                @error('nome')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label for="cargo" class="form-label required-field">{{ __('Cargo') }}</label>
                                <input id="cargo"
                                       class="form-control @error('cargo') is-invalid @enderror"
                                       type="text"
                                       name="cargo"
                                       value="{{old('cargo')}}"
                                       placeholder="Ex: Coordenador do Evento"
                                       required
                                       autocomplete="cargo">

                                @error('cargo')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Upload da assinatura -->
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="fotoAssinatura" class="form-label required-field">Assinatura</label>
                                    <div class="border rounded p-3 bg-light">
                                        <div class="row align-items-center">
                                            <div class="col-12 col-md-6">
                                                <div id="imagem-loader" class="imagem-loader text-center" style="cursor: pointer; border: 2px dashed #dee2e6; border-radius: 8px; padding: 20px; transition: border-color 0.3s;">
                                                    <img id="logo-preview"
                                                         class="img-fluid"
                                                         src="{{asset('/img/nova_imagem.PNG')}}"
                                                         alt="Preview da assinatura"
                                                         style="max-height: 200px; border-radius: 4px;">
                                                </div>
                                                <div style="display: none;">
                                                    <input type="file"
                                                           id="logo-input"
                                                           accept="image/*"
                                                           class="form-control @error('fotoAssinatura') is-invalid @enderror"
                                                           name="fotoAssinatura"
                                                           value="{{ old('fotoAssinatura') }}">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6 mt-3 mt-md-0">
                                                <div class="text-muted">
                                                    <h6>Instruções:</h6>
                                                    <ul class="small mb-0">
                                                        <li>Tamanho mínimo: 1024 x 425 pixels</li>
                                                        <li>Formatos aceitos: JPEG, JPG, PNG</li>
                                                        <li>Clique na área ao lado para selecionar</li>
                                                        <li>Use uma imagem com fundo transparente para melhor resultado</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @error('fotoAssinatura')
                                        <div class="invalid-feedback d-block">
                                            <strong>{{$message}}</strong>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Botão de submit -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-2"></i>{{ __('Cadastrar Assinatura') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    @parent
    <script type="text/javascript">
        $(document).ready(function(){
            $('#imagem-loader').click(function() {
                $('#logo-input').click();
            });

            $('#logo-input').change(function() {
                if (this.files && this.files[0]) {
                    var file = new FileReader();
                    file.onload = function(e) {
                        document.getElementById("logo-preview").src = e.target.result;
                        // Adiciona uma borda verde para indicar sucesso
                        $('#imagem-loader').css('border-color', '#28a745');
                    };
                    file.readAsDataURL(this.files[0]);
                }
            });

            // Efeito hover na área de upload
            $('#imagem-loader').hover(
                function() {
                    $(this).css('border-color', '#007bff');
                },
                function() {
                    if (!$('#logo-input')[0].files.length) {
                        $(this).css('border-color', '#dee2e6');
                    }
                }
            );
        });
    </script>
@endsection
