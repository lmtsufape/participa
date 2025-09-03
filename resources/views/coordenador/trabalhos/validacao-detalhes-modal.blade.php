<div class="modal fade" id="modalValidacaoDetalhes{{$trabalho->id}}" tabindex="-1" aria-labelledby="modalValidacaoDetalhesLabel{{$trabalho->id}}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalValidacaoDetalhesLabel{{$trabalho->id}}">Detalhes da Validação - {{$trabalho->titulo}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="mt-1" id="validacao-correcao-coordenador-{{$trabalho->id}}" action="{{route('revisor.verificarCorrecao', ['trabalho_id' => $trabalho->id])}}" method="POST">
                    @csrf
                    @method('PUT')
                    <fieldset class="mb-3">
                        <legend class="form-label pt-0 h5">Status da correção:</legend>

                        <div class="form-check ml-3">
                            <input class="form-check-input" type="radio" id="coord-completamente-{{$trabalho->id}}" name="status_correcao_{{$trabalho->id}}" value="corrigido" @checked($trabalho->avaliado == 'corrigido') onchange="toggleJustificativaCoordenador({{$trabalho->id}})">
                            <label class="form-check-label" for="coord-completamente-{{$trabalho->id}}">
                                Sim, completamente.
                            </label>
                        </div>

                        <div class="form-check ml-3">
                            <input class="form-check-input" type="radio" id="coord-parcialmente-{{$trabalho->id}}" name="status_correcao_{{$trabalho->id}}" value="corrigido_parcialmente" @checked($trabalho->avaliado == 'corrigido_parcialmente') onchange="toggleJustificativaCoordenador({{$trabalho->id}})">
                            <label class="form-check-label" for="coord-parcialmente-{{$trabalho->id}}">
                                Sim, parcialmente.
                            </label>
                        </div>

                        <div class="form-check ml-3">
                            <input class="form-check-input" type="radio" id="coord-nao_corrigido-{{$trabalho->id}}" name="status_correcao_{{$trabalho->id}}" value="nao_corrigido" @checked($trabalho->avaliado == 'nao_corrigido') onchange="toggleJustificativaCoordenador({{$trabalho->id}})">
                            <label class="form-check-label" for="coord-nao_corrigido-{{$trabalho->id}}">
                                Não.
                            </label>
                        </div>
                    </fieldset>

                    <div class="mb-3" id="justificativa-div-coordenador-{{$trabalho->id}}"
                         style="{{ in_array($trabalho->avaliado, ['corrigido_parcialmente', 'nao_corrigido']) ? 'display: block;' : 'display: none;' }}">
                        <label for="justificativa_correcao_coordenador_{{$trabalho->id}}" class="form-label pt-0 h5">Justificativa</label>
                        <textarea class="form-control"
                                  id="justificativa_correcao_coordenador_{{$trabalho->id}}"
                                  name="justificativa_correcao"
                                  rows="5">{{ $trabalho->justificativa_correcao }}</textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div>
                    <form id="reset-validacao-form-{{$trabalho->id}}"
                        action="{{ route('coord.trabalho.resetarValidacao', $trabalho->id) }}"
                        method="POST"
                        onsubmit="return confirm('Tem certeza que deseja resetar a validação deste trabalho? O avaliador precisará validar a correção novamente.');">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">
                            Apagar Validação
                        </button>
                    </form>
                </div>
                <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" form="validacao-correcao-coordenador-{{$trabalho->id}}">
                        Salvar Alterações
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleJustificativaCoordenador(trabalhoId) {
        const status = document.querySelector(`input[name="status_correcao_${trabalhoId}"]:checked`).value;
        const divJustificativa = document.getElementById(`justificativa-div-coordenador-${trabalhoId}`);
        const textareaJustificativa = document.getElementById(`justificativa_correcao_coordenador_${trabalhoId}`);

        if (status === 'corrigido_parcialmente' || status === 'nao_corrigido') {
            divJustificativa.style.display = 'block';
        } else {
            divJustificativa.style.display = 'none';
        }
    }
</script>
@endpush