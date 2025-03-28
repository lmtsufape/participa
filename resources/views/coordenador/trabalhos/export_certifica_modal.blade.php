<div class="modal fade" id="exportCertificaModal" tabindex="-1" aria-labelledby="exportCertificaModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fs-5" id="deleteModalLabel">Exportação para o Certifica</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="certificaForm" action="{{ route('evento.downloadTrabalhosCertifica', $evento) }}"
                    method="post">
                    @csrf

                    <div class="form-group">
                        <label for="ch" class="form-label">Insira as horas da atividade<strong
                                style="color: red">*</strong>:</label>
                        <input type="text" name="ch" id="ch" value="{{ old('ch') }}"
                            class="form-control @error('ch') is-invalid @enderror" required>

                        @error('ch')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        @if ($coautoresSemCpfPorTrabalho->isNotEmpty())
                            <button type="button" class="btn btn-success" onclick="validarFormulario()">Baixar</button>
                        @else
                            <button type="submit" class="btn btn-success">Baixar</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if ($coautoresSemCpfPorTrabalho->isNotEmpty())
    <div class="modal fade" id="confirmacaoModal" tabindex="-1" aria-labelledby="confirmacaoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5">Aviso</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Os coautores listados abaixo não possuem CPF cadastrado e, por esse motivo, não serão incluídos
                        na importação.</p>

                    <ul>
                        @foreach ($coautoresSemCpfPorTrabalho as $trabalho => $coautores)
                            <li>
                                <strong>{{ $trabalho }}</strong>
                                <ul>
                                    @foreach ($coautores as $coautor)
                                        <li>{{ $coautor->user->name . ' - ' . $coautor->user->email }}</li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-success" form="certificaForm">Baixar</button>
                </div>
            </div>
        </div>
    </div>
@endif

<script>
    function validarFormulario() {
        const form = document.getElementById('certificaForm');
        const inputCh = document.getElementById('ch');

        // Verifica se o input é válido (incluindo required e pattern)
        if (inputCh.checkValidity()) {
            // Se válido, abre o segundo modal
            $('#confirmacaoModal').modal('show');
        } else {
            // Se inválido, dispara validação nativa
            inputCh.reportValidity();
        }
    }

    $('#certificaForm').on('submit', function() {
        $('#exportCertificaModal').modal('hide');
        $('#confirmacaoModal').modal('hide');
    });
</script>
