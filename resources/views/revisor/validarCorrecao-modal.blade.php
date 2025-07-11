

<div class="modal fade" id="validacaoCorrecaoModal{{$trabalho->id}}" tabindex="-1" aria-labelledby="#validacaoCorrecaoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="#validacaoCorrecaoModalLabel">Validação das correções</h1>
          <button type="button" class="btn" data-dismiss="modal" aria-label="Close">X</button>
        </div>
        <div class="modal-body">
            <form class="mt-1" id="validacao-correcao-{{$trabalho->id}}" action="{{route('revisor.verificarCorrecao', ['trabalho_id' => $trabalho->id])}}" method="POST">
                @csrf
                @method('PUT')
                <fieldset class="mb-3">
                    <legend class="form-label pt-0 h5">O participante fez as correções indicadas?</legend>

                    <div class="form-check ml-3">
                        <input class="form-check-input" type="radio" id="completamente-{{$trabalho->id}}" name="status_correcao" value="corrigido" @checked(old('status_correcao', $trabalho->avaliado) == 'corrigido')>
                        <label class="form-check-label" for="completamente-{{$trabalho->id}}">
                            Sim, completamente.
                        </label>
                    </div>

                    <div class="form-check ml-3">
                        <input class="form-check-input" type="radio" id="parcialmente-{{$trabalho->id}}" name="status_correcao" value="corrigido_parcialmente" @checked(old('status_correcao', $trabalho->avaliado) == 'corrigido_parcialmente')>
                        <label class="form-check-label" for="parcialmente-{{$trabalho->id}}">
                            Sim, parcialmente.
                        </label>
                    </div>

                    <div class="form-check ml-3">
                        <input class="form-check-input" type="radio" id="nao_corrigido-{{$trabalho->id}}" name="status_correcao" value="nao_corrigido" @checked(old('status_correcao', $trabalho->avaliado) == 'nao_corrigido')>
                        <label class="form-check-label" for="nao_corrigido-{{$trabalho->id}}">
                            Não.
                        </label>
                    </div>
                </fieldset>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            <button type="submit" class="btn btn-success" form="validacao-correcao-{{$trabalho->id}}">
                Submeter
            </button>
        </div>
      </div>
    </div>
</div>