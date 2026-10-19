document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('arquivo-versao-final');
    if (!input) return;

    const error = document.getElementById('erro-tamanho-versao-final');
    const maxBytes = 5 * 1024 * 1024; // Mesmo limite do Laravel: max:5120 KB.
    const modalElement = document.getElementById('modal-limite-trabalho-completo');
    const modal = modalElement && window.bootstrap?.Modal
        ? window.bootstrap.Modal.getOrCreateInstance(modalElement) : null;
    modalElement?.addEventListener('hidden.bs.modal', () => input.focus());

    function validateSize() {
        const file = input.files[0];
        if (file && file.size > maxBytes) {
            input.value = '';
            input.classList.add('is-invalid');
            input.setAttribute('aria-invalid', 'true');
            error.hidden = false;
            if (modal) {
                modal.show();
            } else {
                window.alert('O tamanho máximo permitido é 5 MB. Selecione um arquivo menor para continuar.');
                input.focus();
            }
            return false;
        }
        if (file) {
            input.classList.remove('is-invalid');
            input.removeAttribute('aria-invalid');
            error.hidden = true;
        }
        return true;
    }

    input.addEventListener('change', validateSize);
    input.form.addEventListener('submit', event => {
        if (!validateSize()) {
            event.preventDefault();
            event.stopImmediatePropagation();
        }
    }, true);
});
