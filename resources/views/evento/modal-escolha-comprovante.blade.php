<div class="modal fade" id="modalEscolhaComprovante" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
                <h5 class="modal-title">Escolha a Modalidade de Inscrição</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center d-flex flex-column gap-3">
                <p>Selecione abaixo o tipo de vínculo que deseja comprovar para este evento:</p>
                
                <button class="btn btn-outline-primary py-3" data-bs-toggle="modal" data-bs-target="#modalInscricaoEstudante">
                    Inscrição como Estudante
                </button>
                
                <button class="btn btn-outline-primary py-3" data-bs-toggle="modal" data-bs-target="#modalInscricaoMovimentoSocial">
                    Inscrição como Membro de Movimento Social
                </button>
            </div>
        </div>
    </div>
</div>

<!-- O modal original de estudante permanece intacto -->
@include('evento.modal-inscricao-estudante')

<!-- Novo Modal para Movimento Social -->
<div class="modal fade" id="modalInscricaoMovimentoSocial" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
                <h5 class="modal-title">Solicitação de Inscrição - Movimento Social</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('inscricao.movimentosocial.store', $evento) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <p>Por favor, anexe um documento que comprove sua participação ativa no movimento social.</p>
                    <p>O arquivo deve ser no formato PDF, JPG, JPEG ou PNG e ter no máximo 5MB.</p>

                    <div class="mb-3">
                        <label for="comprovante_ms" class="form-label">Anexar Comprovante</label>
                        <input class="form-control" type="file" id="comprovante_ms" name="comprovante" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" style="background-color: #114048ff; border-color: #114048ff;">
                        Enviar Solicitação
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>