<div class="modal fade" id="validacaoCorrecaoModal{{$trabalho->id}}" tabindex="-1" aria-labelledby="validacaoCorrecaoModalLabel{{$trabalho->id}}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="validacaoCorrecaoModalLabel{{$trabalho->id}}">Validação das correções</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="mt-1" id="validacao-correcao-{{$trabalho->id}}" action="{{route('revisor.verificarCorrecao', ['trabalho_id' => $trabalho->id])}}" method="POST">
                    @csrf
                    @method('PUT')
                    <fieldset class="mb-3">
                        <legend class="form-label pt-0 h5">O participante fez as correções indicadas?</legend>

                        <div class="form-check ml-3">
                            <input class="form-check-input" type="radio" id="completamente-{{$trabalho->id}}" name="status_correcao_{{$trabalho->id}}" value="corrigido" @checked(old('status_correcao', $trabalho->avaliado) == 'corrigido') onchange="toggleJustificativa({{$trabalho->id}})">
                            <label class="form-check-label" for="completamente-{{$trabalho->id}}">
                                Sim, completamente.
                            </label>
                        </div>

                        <div class="form-check ml-3">
                            <input class="form-check-input" type="radio" id="parcialmente-{{$trabalho->id}}" name="status_correcao_{{$trabalho->id}}" value="corrigido_parcialmente" @checked(old('status_correcao', $trabalho->avaliado) == 'corrigido_parcialmente') onchange="toggleJustificativa({{$trabalho->id}})">
                            <label class="form-check-label" for="parcialmente-{{$trabalho->id}}">
                                Sim, parcialmente.
                            </label>
                        </div>

                        <div class="form-check ml-3">
                            <input class="form-check-input" type="radio" id="nao_corrigido-{{$trabalho->id}}" name="status_correcao_{{$trabalho->id}}" value="nao_corrigido" @checked(old('status_correcao', $trabalho->avaliado) == 'nao_corrigido') onchange="toggleJustificativa({{$trabalho->id}})">
                            <label class="form-check-label" for="nao_corrigido-{{$trabalho->id}}">
                                Não.
                            </label>
                        </div>
                    </fieldset>

                    <div class="mb-3" id="justificativa-div-{{$trabalho->id}}" style="display: none;">
                        <label for="justificativa_correcao_{{$trabalho->id}}" class="form-label pt-0 h5">Justificativa</label>
                        <p class="text-danger small mt-1">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <strong>Lembrete:</strong> sugerimos fortemente a inclusão de uma justificativa no caso de <em>Sim, parcialmente</em> ou <em>Não</em>.
                        </p>
                        <textarea class="form-control"
                                  id="justificativa_correcao_{{$trabalho->id}}"
                                  name="justificativa_correcao"
                                  rows="4"
                                  placeholder="Explique o motivo da aprovação parcial ou reprovação.">{{ old('justificativa_correcao', $trabalho->justificativa_correcao) }}</textarea>
                    </div>

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

@push('scripts')
<script>
    function toggleJustificativa(trabalhoId) {
        const status = document.querySelector(`input[name="status_correcao_${trabalhoId}"]:checked`).value;
        const divJustificativa = document.getElementById(`justificativa-div-${trabalhoId}`);
        const textareaJustificativa = document.getElementById(`justificativa_correcao_${trabalhoId}`);

        if (status === 'corrigido_parcialmente' || status === 'nao_corrigido') {
            divJustificativa.style.display = 'block';
        } else {
            divJustificativa.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
    });
</script>
@endpush
