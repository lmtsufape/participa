document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-proponentes]').forEach(section => {
        section.addEventListener('click', event => {
            if (event.target.closest('[data-remover-proponente]')) {
                event.target.closest('[data-proponente]').remove();
            }
            if (event.target.closest('[data-adicionar-proponente]')) {
                const row = document.createElement('div');
                row.className = 'input-group mb-2';
                row.setAttribute('data-proponente', '');
                row.innerHTML = '<input type="text" class="form-control" name="proponentes[]" maxlength="255" aria-label="Nome do proponente ou ministrante"><button type="button" class="btn btn-outline-secondary" data-remover-proponente>Remover</button>';
                section.querySelector('[data-proponentes-lista]').append(row);
                row.querySelector('input').focus();
            }
        });
    });
});
