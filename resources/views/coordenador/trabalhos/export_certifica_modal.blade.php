<div class="modal fade" id="exportCertificaModal" tabindex="-1" aria-labelledby="exportCertificaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fs-5" id="deleteModalLabel">Exportação para o Certifica</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="certificaForm" action="{{route('evento.downloadTrabalhosCertifica', $evento)}}" method="post">
                    @csrf

                    <div class="form-group">
                        <label for="ch" class="form-label">Insira as horas da atividade<strong style="color: red">*</strong>:</label>
                        <input type="text" name="ch" id="ch" value="{{old('ch')}}" class="form-control @error('ch') is-invalid @enderror" required>

                        @error('ch')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{$message}}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success">Baixar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $('#certificaForm').on('submit', function() {
        $('#exportCertificaModal').modal('hide');
    });
</script>
