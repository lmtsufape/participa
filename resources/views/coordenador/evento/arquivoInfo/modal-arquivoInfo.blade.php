<!-- Button trigger modal -->
<button type="button"
    class="btn btn-primary"
    data-bs-toggle="modal"
    data-bs-target="#exampleModal">
    Adicionar arquivo
</button>

<!-- Modal -->
<div class="modal fade"
    id="exampleModal"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"
                    id="exampleModalLabel">Adicionar arquivo</h5>
                <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-sm-12">
                        <h6 class="card-subtitle mb-2 text-muted">Adicione um arquivo para mostrar na parte de
                            informações</h6>
                        <form method="POST"
                            action="{{ route('coord.arquivos-adicionais.store', $evento) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden"
                                name="eventoId"
                                value="{{ $evento->id }}">
                            <p class="card-text">
                            <div class="row justify-content-center">
                                <div class="col-sm-12">
                                    <label for="nome"
                                        class="col-form-label">{{ __('Nome do arquivo') }}</label>
                                    <input id="nome"
                                        type="text"
                                        class="form-control @error('nome') is-invalid @enderror"
                                        name="nome"
                                        value="{{ old('nome') }}"
                                        required
                                        autocomplete="nome"
                                        autofocus>
                                </div>
                                <div class="col-sm-12">
                                    <label for="arquivo"
                                        class="col-form-label">{{ __('Arquivo') }}</label>
                                    <input id="arquivo"
                                        type="file"
                                        class="form-control @error('arquivo') is-invalid @enderror"
                                        name="arquivo"
                                        required
                                        autocomplete="arquivo"
                                        autofocus>
                                </div>
                            </div>
                            </p>
                            <div class="modal-footer">
                                <button type="button"
                                    class="btn btn-secondary"
                                    data-dismiss="modal">Fechar</button>
                                <button type="submit"
                                    class="btn btn-primary">
                                    {{ __('Finalizar') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
