document.addEventListener('DOMContentLoaded', () => {
    const error = document.getElementById('presenca-erro');
    document.querySelectorAll('.js-presenca').forEach(input => {
        input.addEventListener('change', async () => {
            const desired = input.checked;
            const status = input.closest('td').querySelector('.js-presenca-status');
            input.disabled = true;
            status.textContent = 'Salvando…';
            error.classList.add('d-none');
            try {
                const response = await fetch(input.dataset.url, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ is_presente: desired }),
                });
                if (!response.ok || response.redirected) throw new Error('save');
                const data = await response.json();
                if (typeof data.is_presente !== 'boolean') throw new Error('response');
                input.checked = data.is_presente;
                status.textContent = data.is_presente ? 'Presença salva' : 'Marcação removida';
            } catch (_) {
                input.checked = !desired;
                status.textContent = 'Alteração não confirmada';
                error.textContent = 'Não foi possível confirmar a alteração. Confira sua conexão e atualize a página antes de tentar novamente.';
                error.classList.remove('d-none');
            } finally {
                input.disabled = false;
            }
        });
    });
});
