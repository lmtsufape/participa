<div class="modal fade" id="modalConfirmInscricao" tabindex="-1" aria-labelledby="modalConfirmInscricaoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalConfirmInscricaoLabel">{{ __('Confirmação') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Fechar') }}"></button>
      </div>
      <div class="modal-body">
        {{ __('Para submeter um trabalho, você precisa se inscrever no evento primeiro. Deseja prosseguir para a inscrição?') }}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          {{ __('Cancelar') }}
        </button>
        <button type="button"
                class="btn btn-my-success"
                id="btnConfirmarInscricao">
          {{ __('Sim, quero me inscrever') }}
        </button>
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const botoesCaso2 = document.querySelectorAll('.btn-caso2-confirm');

        // Exibe o modal de confirmacao
        botoesCaso2.forEach(function(btn) {
            btn.addEventListener('click', function(event) {
            event.preventDefault();
            const confirmModalEl = document.getElementById('modalConfirmInscricao');
            const confirmModal = new bootstrap.Modal(confirmModalEl);
            confirmModal.show();

            });
        });

        // Esconde o modal de confirmacao e exibe o modal de inscricao
        const btnConfirmar = document.getElementById('btnConfirmarInscricao');
        if (btnConfirmar) {
        btnConfirmar.addEventListener('click', function() {

            const confirmModalEl = document.getElementById('modalConfirmInscricao');
            const bsConfirmModal = bootstrap.Modal.getInstance(confirmModalEl);
            if (bsConfirmModal) {
            bsConfirmModal.hide();
            }

            const inscricaoModalEl = document.getElementById('modalInscrever');
            const inscricaoModal = new bootstrap.Modal(inscricaoModalEl);
            inscricaoModal.show();

        });
        }
    });
</script>
