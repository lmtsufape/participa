document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-etapa-modalidade]').forEach(section => {
        const checkbox = section.querySelector('[data-etapa-checkbox]');
        const dates = section.querySelector('[data-etapa-datas]');
        const inputs = [...dates.querySelectorAll('input')];
        const panel = section.querySelector('[data-etapa-impacto]');
        const message = section.querySelector('[data-etapa-mensagem]');
        const list = section.querySelector('[data-etapa-lista]');
        const confirmation = section.querySelector('[data-etapa-confirmacao]');
        const confirm = section.querySelector('[data-etapa-confirmar]');
        const form = section.closest('form');
        let savedDates = inputs.map(input => input.value || input.dataset.original || '');
        let generation = 0;
        let pending = false;
        let token = '';

        function updateDates() {
            dates.hidden = !checkbox.checked;
            checkbox.setAttribute('aria-expanded', String(checkbox.checked));
            inputs.forEach(input => {
                input.disabled = !checkbox.checked;
                input.required = checkbox.checked;
                if (!checkbox.checked) input.value = '';
            });
            if (section.dataset.etapa === 'avaliacao') {
                const during = form.querySelector('[name="avaliacaoDuranteSubmissao"]');
                if (during) {
                    during.disabled = !checkbox.checked;
                    during.closest('.form-check').hidden = !checkbox.checked;
                }
            }
        }

        async function change() {
            const current = ++generation;
            confirmation.value = '';
            panel.hidden = true;
            confirm.hidden = true;
            list.replaceChildren();
            pending = false;
            if (checkbox.checked) {
                inputs.forEach((input, i) => { input.value = savedDates[i]; });
                updateDates();
                return;
            }
            savedDates = inputs.map((input, i) => input.value || savedDates[i]);
            updateDates();
            if (section.dataset.existente !== '1') return;

            pending = true;
            panel.hidden = false;
            message.textContent = 'Consultando os trabalhos desta modalidade…';
            try {
                const url = new URL(section.dataset.impactoUrl, window.location.href);
                url.searchParams.set('etapa', section.dataset.etapa);
                const response = await fetch(url, { headers: { Accept: 'application/json' }, cache: 'no-store' });
                if (!response.ok) throw new Error('Falha ao consultar');
                const data = await response.json();
                if (current !== generation) return;
                if (!data.quantidade) {
                    pending = false;
                    panel.hidden = true;
                    return;
                }
                token = data.confirmacao;
                const label = { avaliacao: 'avaliação', correcao: 'correção', validacao: 'validação da correção' }[section.dataset.etapa];
                message.textContent = `${data.quantidade} trabalho(s) com ${label} registrada. Confira antes de desativar:`;
                const table = document.createElement('table');
                table.className = 'table table-sm';
                const header = table.createTHead().insertRow();
                ['ID', 'Título', 'Autores'].forEach(text => {
                    const cell = document.createElement('th');
                    cell.scope = 'col';
                    cell.textContent = text;
                    header.append(cell);
                });
                const body = table.createTBody();
                data.trabalhos.forEach(work => {
                    const row = body.insertRow();
                    [work.id, work.titulo, work.autores.join('; ') || 'Autor não disponível'].forEach(value => {
                        row.insertCell().textContent = value;
                    });
                });
                list.append(table);
                confirm.hidden = false;
            } catch (error) {
                if (current !== generation) return;
                message.textContent = 'Não foi possível conferir os trabalhos. Reative a etapa e tente novamente antes de salvar.';
            }
        }
        checkbox.addEventListener('change', change);
        confirm.addEventListener('click', () => {
            confirmation.value = token;
            pending = false;
            confirm.hidden = true;
            message.textContent += ' Desativação confirmada. Clique em salvar para concluir.';
        });
        section.querySelector('[data-etapa-cancelar]').addEventListener('click', () => {
            checkbox.checked = true;
            change();
        });
        form.addEventListener('submit', event => {
            if (pending) {
                event.preventDefault();
                panel.scrollIntoView({ block: 'center', behavior: 'smooth' });
                if (!confirm.hidden) confirm.focus();
            }
        });
        if (!checkbox.checked && section.dataset.existente === '1') change();
        else updateDates();
        if (section.dataset.reabrir === '1' && window.bootstrap?.Modal) {
            const modal = section.closest('.modal');
            if (modal && !modal.dataset.etapasReaberto) {
                modal.dataset.etapasReaberto = '1';
                window.bootstrap.Modal.getOrCreateInstance(modal).show();
            }
        }
    });
});
