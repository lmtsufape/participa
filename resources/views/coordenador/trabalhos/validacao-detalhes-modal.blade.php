<div class="modal fade" id="modalValidacaoDetalhes{{$trabalho->id}}" tabindex="-1" aria-labelledby="modalValidacaoDetalhesLabel{{$trabalho->id}}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Detalhes da Validação - {{$trabalho->titulo}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="mt-1" id="validacao-correcao-coordenador-{{$trabalho->id}}" action="{{route('revisor.verificarCorrecao', ['trabalho_id' => $trabalho->id])}}" method="POST">
                    @csrf
                    @method('PUT')
                    <fieldset class="mb-3">
                        <legend class="form-label pt-0 h5">Status da correção:</legend>

                        <div class="form-check ml-3">
                            <input class="form-check-input" type="radio" id="coord-completamente-{{$trabalho->id}}" name="status_correcao_{{$trabalho->id}}" value="corrigido" @checked($trabalho->avaliado == 'corrigido')>
                            <label class="form-check-label" for="coord-completamente-{{$trabalho->id}}">Sim, completamente.</label>
                        </div>

                        <div class="form-check ml-3">
                            <input class="form-check-input" type="radio" id="coord-parcialmente-{{$trabalho->id}}" name="status_correcao_{{$trabalho->id}}" value="corrigido_parcialmente" @checked($trabalho->avaliado == 'corrigido_parcialmente')>
                            <label class="form-check-label" for="coord-parcialmente-{{$trabalho->id}}">Sim, parcialmente.</label>
                        </div>

                        <div class="form-check ml-3">
                            <input class="form-check-input" type="radio" id="coord-nao_corrigido-{{$trabalho->id}}" name="status_correcao_{{$trabalho->id}}" value="nao_corrigido" @checked($trabalho->avaliado == 'nao_corrigido')>
                            <label class="form-check-label" for="coord-nao_corrigido-{{$trabalho->id}}">Não.</label>
                        </div>
                    </fieldset>

                    <div class="mb-3">
                        <label for="justificativa_correcao" class="form-label pt-0 h5">Justificativa / Observações (Opcional)</label>
                        <textarea class="form-control" name="justificativa_correcao" rows="5">{{ $trabalho->justificativa_correcao }}</textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <form id="reset-validacao-form-{{$trabalho->id}}" action="{{ route('coord.trabalho.resetarValidacao', $trabalho->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja resetar a validação?');">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">Apagar Validação</button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" form="validacao-correcao-coordenador-{{$trabalho->id}}">Salvar Alterações</button>
            </div>
        </div>
    </div>
</div>