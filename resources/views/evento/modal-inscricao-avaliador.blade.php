<div class="modal fade" id="modalInscreverAvaliador" tabindex="-1" role="dialog" aria-labelledby="modalInscreverAvaliadorLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header position-relative" style="background-color: #114048ff; color: white;">
                <h5 class="modal-title w-100 text-center m-0" id="modalInscreverAvaliadorLabel">
                    Quero ser um avaliador
                </h5>
                <button type="button" class="btn-close btn-close-white position-absolute end-0 top-50 translate-middle-y me-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('coord.candidatoAvaliador.store') }}" method="POST">
                @csrf
                <input type="hidden" name="evento_id"  value="{{ $evento->id }}">
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="lattes_link" class="form-label"><strong>Link para o currículo Lattes</strong> <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" id="lattes_link" name="lattes_link" placeholder="https://lattes.cnpq.br/..." required>
                    </div>

                    <div class="mb-3">
                        <label for="lattes_resumo" class="form-label"><strong>Resumo simplificado do Lattes</strong> <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="lattes_resumo" name="lattes_resumo" rows="4" placeholder="Descreva brevemente sua formação, área de atuação e principais publicações..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Em qual/quais eixo(s) temático(s) você gostaria de contribuir?</strong> <span class="text-danger">*</span></label>
                        <small class="d-block text-muted mb-2">Selecione até 03 eixos de sua preferência. O primeiro é obrigatório.</small>

                         @foreach($areas as $area)
                            <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                    name="eixos[]"
                                    value="{{ $area->id }}"
                                    id="eixo_{{ $area->id }}">
                            <label class="form-check-label" for="eixo_{{ $area->id }}">
                                {{ $area->nome }}
                            </label>
                            </div>
                        @endforeach
                        <span id="eixos_error" class="text-danger d-none mt-1">
                            Você já atingiu o limite de 3 eixos.
                        </span>
                        </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Já avaliou resumos do CBA em outros anos? </strong><span class="text-danger">*</span></label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="avaliou_antes" id="avaliou_sim" value="sim" required>
                                <label class="form-check-label" for="avaliou_sim">Sim</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="avaliou_antes" id="avaliou_nao" value="nao" required>
                                <label class="form-check-label" for="avaliou_nao">Não</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Disponibilidade para avaliar em outros idiomas?</strong></label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="idioma_nenhum" name="idiomas[]" value="nao">
                                <label class="form-check-label" for="idioma_nenhum">Não</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="idioma_espanhol" name="idiomas[]" value="espanhol">
                                <label class="form-check-label" for="idioma_espanhol">Espanhol</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="idioma_ingles" name="idiomas[]" value="ingles">
                                <label class="form-check-label" for="idioma_ingles">Inglês</label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" style="background-color: #114048ff; border-color: #114048ff;">
                        Enviar Candidatura
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
  // Limita a 3 checkboxes selecionadas
  document.querySelectorAll("input[name='eixos[]']").forEach(cb => {
    cb.addEventListener('change', function() {
      const checked = document.querySelectorAll("input[name='eixos[]']:checked");
      const errorSpan  = document.getElementById('eixos_error');
      if (checked.length > 3) {
        this.checked = false;
        errorSpan.classList.remove('d-none');
      } else {
        errorSpan.classList.add('d-none');
      }
    });
  });
</script>
@endpush
