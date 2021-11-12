<!-- Modal de editar arquivo -->
<div class="modal fade"
    id="modalEditarArea{{ $arquivo->id }}"
    tabindex="-1"
    role="dialog"
    aria-labelledby="#label"
    aria-hidden="true">
    <div class="modal-dialog"
        role="document">
        <div class="modal-content">
            <div class="modal-header"
                style="background-color: #114048ff; color: white;">
                <h5 class="modal-title"
                    id="#label">Editar arquivo {{ $arquivo->nome }}</h5>
                <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                    style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditarArea{{ $arquivo->id }}"
                action="{{ route('coord.arquivos-adicionais.update', $arquivo) }}"
                method="POST"
                enctype="multipart/form-data">
                @method('PUT')
                @csrf
                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{$error}}</p>
                        @endforeach
                    </div>
                @endif
                <input type="hidden"
                    name="editarAreaId"
                    value="{{ $arquivo->id }}">
                <div class="container">
                    <div class="row form-group">
                        <div class="col-sm-12"
                            style="margin-top: 20px; margin-bottom: 20px;">
                            <label for="nome">Nome*</label>
                            <input id="nome"
                                type="text"
                                class="form-control @error('nome') is-invalid @enderror"
                                name="nome"
                                value="{{old('nome', $arquivo->nome)}}">
                            @error('nome')
                                <span class="invalid-feedback"
                                    role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-sm-12">
                            <label for="arquivo"
                                class="col-form-label">{{ __('Arquivo:') }}</label>
                                <a href="{{ asset('storage/'.$arquivo->path) }}" target="_black">Arquivo atual</a>
                                <br>
                                <small>Para trocar o arquivo envie um novo.</small>
                            <div class="custom-file">
                                <input type="file"
                                    class="form-control @error('arquivo') is-invalid @enderror"
                                    name="arquivo">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal">Cancelar</button>
                <button type="submit"
                    class="btn btn-primary"
                    form="formEditarArea{{ $arquivo->id }}">Atualizar</button>
            </div>
        </div>
    </div>
</div>
