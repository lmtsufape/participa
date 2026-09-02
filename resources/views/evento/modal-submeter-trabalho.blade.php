<div class="modal fade" id="modalTrabalho" tabindex="-1" role="dialog" aria-labelledby="modalTrabalho" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">{{ __('Submeter nova versão') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('trabalho.novaVersao') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-sm-12">
                            @if ($hasFile)
                                <input type="hidden" name="trabalhoId" value="" id="trabalhoNovaVersaoId">
                            @endif
                            <input type="hidden" name="eventoId" value="{{ $evento->id }}">
                            {{-- Arquivo --}}
                            <label for="nomeTrabalho" class="col-form-label">{{ __('Arquivo') }}</label>
                            <div class="custom-file">
                                <input type="file" class="filestyle" data-placeholder="Nenhum arquivo"
                                    data-text="Selecionar" data-btnClass="btn-primary-lmts" name="arquivo">
                            </div>
                            <small>{{ __('O arquivo Selecionado deve ser no formato PDF de até 2mb') }}.</small>
                            @error('arquivo')
                                <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Fechar') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Salvar') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
