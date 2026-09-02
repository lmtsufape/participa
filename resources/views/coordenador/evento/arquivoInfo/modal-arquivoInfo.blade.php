<!-- Button trigger modal -->
<button type="button"
    class="btn btn-primary"
    data-bs-toggle="modal"
    data-bs-target="#exampleModal">
    Adicionar arquivo
</button>

<!-- Modal -->
<div class="modal fade"
    id="exampleModal"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"
                    id="exampleModalLabel">Adicionar arquivo</h5>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-sm-12">
                        <h6 class="card-subtitle mb-2 text-muted">Adicione um arquivo para mostrar na parte de
                            informações</h6>
                        <form method="POST"
                            action="{{ route('coord.arquivos-adicionais.store', $evento) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden"
                                name="eventoId"
                                value="{{ $evento->id }}">
                            <p class="card-text">
                            <div class="row justify-content-center">
                                <div class="col-sm-12">
                                    <label for="nome"
                                        class="col-form-label fw-bold">{{ __('Nome do arquivo') }}</label>
                                    <input id="nome"
                                        type="text"
                                        class="form-control @error('nome') is-invalid @enderror"
                                        name="nome"
                                        value="{{ old('nome') }}"
                                        required
                                        autocomplete="nome"
                                        autofocus>
                                </div>
                                <div class="col-sm-12">
                                    <label for="arquivo"
                                        class="col-form-label fw-bold">{{ __('Arquivo') }}</label>
                                    <input id="arquivo"
                                        type="file"
                                        class="form-control @error('arquivo') is-invalid @enderror"
                                        name="arquivo"
                                        required
                                        autocomplete="arquivo"
                                        autofocus>
                                </div>
                            </div>
                            </p>
                            <div class="modal-footer">
                                <button type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal">Fechar</button>
                                <button type="submit"
                                    class="btn btn-primary" id="btnSalvarPdf" disabled>
                                    {{ __('Finalizar') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    // IDs corretos que correspondem ao HTML
    const fileInput = document.getElementById('arquivo');
    const nomeInput = document.getElementById('nome');
    const btnSalvar = document.getElementById('btnSalvarPdf');
    const modal = document.getElementById('exampleModal');

    // Função para validar formulário completo
    function validarFormulario() {
        const arquivo = fileInput.files[0];
        const nome = nomeInput.value.trim();

        // Verifica se ambos os campos estão preenchidos
        const isValid = arquivo && nome.length > 0;

        // Habilita/desabilita botão
        btnSalvar.disabled = !isValid;

        return isValid;
    }

    // Função para validar arquivo especificamente
    function validarArquivo() {
        const arquivo = fileInput.files[0];

        if (arquivo) {
            fileInput.classList.remove('is-invalid');
            fileInput.classList.add('is-valid');
            mostrarFeedback(`✅ Arquivo selecionado: ${arquivo.name}`, 'success', 'arquivo-feedback');
        } else {
            fileInput.classList.remove('is-valid');
            fileInput.classList.add('is-invalid');
            mostrarFeedback('❌ Selecione um arquivo', 'error', 'arquivo-feedback');
        }

        validarFormulario();
    }

    // Função para validar nome
    function validarNome() {
        const nome = nomeInput.value.trim();

        if (nome.length > 0) {
            nomeInput.classList.remove('is-invalid');
            nomeInput.classList.add('is-valid');
            removerFeedback('nome-feedback');
        } else {
            nomeInput.classList.remove('is-valid');
            nomeInput.classList.add('is-invalid');
            mostrarFeedback('❌ Digite um nome para o arquivo', 'error', 'nome-feedback');
        }

        validarFormulario();
    }

    // Função para mostrar feedback
    function mostrarFeedback(mensagem, tipo, feedbackId) {
        removerFeedback(feedbackId);

        const feedback = document.createElement('div');
        feedback.id = feedbackId;
        feedback.className = `mt-2 text-${tipo === 'success' ? 'success' : 'danger'} small`;
        feedback.innerHTML = `<i class="fas fa-${tipo === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> ${mensagem}`;

        if (feedbackId === 'arquivo-feedback') {
            fileInput.parentNode.appendChild(feedback);
        } else {
            nomeInput.parentNode.appendChild(feedback);
        }
    }

    // Função para remover feedback
    function removerFeedback(feedbackId) {
        const feedback = document.getElementById(feedbackId);
        if (feedback) feedback.remove();
    }

    // Event listeners
    fileInput.addEventListener('change', validarArquivo);
    nomeInput.addEventListener('input', validarNome);
    nomeInput.addEventListener('blur', validarNome);

    // Resetar modal quando abrir
    modal.addEventListener('show.bs.modal', function() {
        fileInput.value = '';
        nomeInput.value = '';
        btnSalvar.disabled = true;
        fileInput.classList.remove('is-valid', 'is-invalid');
        nomeInput.classList.remove('is-valid', 'is-invalid');
        removerFeedback('arquivo-feedback');
        removerFeedback('nome-feedback');
    });

    // Validação antes do submit
    modal.querySelector('form').addEventListener('submit', function(e) {
        if (!validarFormulario()) {
            e.preventDefault();
            alert('⚠️ Por favor, preencha todos os campos obrigatórios!');
            return false;
        }
    });
});
</script>
