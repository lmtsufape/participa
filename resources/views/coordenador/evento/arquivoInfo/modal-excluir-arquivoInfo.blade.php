<!-- Modal de exclusão do arquivo -->
<div class="modal fade"
    id="modalExcluirArea{{ $arquivo->id }}"
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
                    id="#label">Confirmação</h5>
                <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                    style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja excluir esse arquivo?
            </div>
            <div class="modal-footer">
                <button type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal">Não</button>
                <button type="submit"
                    class="btn btn-primary"
                    form="formExcluirArea{{ $arquivo->id }}">Sim</button>
            </div>
        </div>
    </div>
</div>
