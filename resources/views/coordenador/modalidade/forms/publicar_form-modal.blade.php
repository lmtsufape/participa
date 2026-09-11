<div
    class="modal fade"
    id="modalPublicarForm{{ $form->id }}"
    tabindex="-1"
    aria-labelledby="modalPublicarFormLabel{{ $form->id }}"
    aria-hidden="true"
>
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-sm rounded-3 overflow-hidden">

            {{-- Cabeçalho --}}
            <div class="modal-header bg-my-primary text-white border-bottom  px-4 py-3">
                <div class="d-flex align-items-center text-white gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h5
                                class="modal-title fw-semibold mb-0"
                                id="modalPublicarFormLabel{{ $form->id }}"
                            >
                                @if ($form->form_anterior_id)
                                    Publicar nova versão
                                @else
                                    Publicar formulário
                                @endif
                            </h5>

                            @if ($form->form_anterior_id)
                                <span class="badge bg-light border fw-normal">
                                    Versão {{ $form->versao }}
                                </span>
                            @endif
                        </div>

                        <small class="text-light">
                            Confirme antes de disponibilizar esta versão.
                        </small>
                    </div>
                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Fechar"
                ></button>
            </div>

            {{-- Conteúdo --}}
            <div class="modal-body px-4 py-4">

                @if ($form->form_anterior_id)

                    <p class="mb-4">
                        A <strong>versão {{ $form->versao }}</strong> passará a
                        ser a versão atual deste formulário.
                    </p>

                    <div class="border rounded-3 bg-light p-3">

                        <div class="d-flex gap-3 mb-3">
                            <i class="bi bi-check-circle text-success"></i>

                            <div>
                                <div class="fw-semibold small">
                                    Nova versão ativa
                                </div>

                                <div class="text-muted small">
                                    Novas avaliações utilizarão a versão
                                    {{ $form->versao }}.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-3 mb-3">
                            <i class="bi bi-clock-history text-secondary"></i>

                            <div>
                                <div class="fw-semibold small">
                                    Histórico preservado
                                </div>

                                <div class="text-muted small">
                                    A versão anterior continuará disponível
                                    no histórico do formulário.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <i class="bi bi-shield-check text-secondary"></i>

                            <div>
                                <div class="fw-semibold small">
                                    Respostas mantidas
                                </div>

                                <div class="text-muted small">
                                    Respostas já registradas permanecerão
                                    vinculadas à versão em que foram realizadas.
                                </div>
                            </div>
                        </div>

                    </div>

                @else

                    <p class="mb-4">
                        Este formulário será publicado pela primeira vez e
                        ficará disponível para utilização nas avaliações.
                    </p>

                    <div class="border rounded-3 bg-light p-3">

                        <div class="d-flex gap-3 mb-3">
                            <i class="bi bi-check-circle text-success"></i>

                            <div>
                                <div class="fw-semibold small">
                                    Formulário disponível
                                </div>

                                <div class="text-muted small">
                                    Após a publicação, esta será a versão utilizada
                                    nas novas avaliações.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <i class="bi bi-pencil-square text-secondary"></i>

                            <div>
                                <div class="fw-semibold small">
                                    Edição continua disponível
                                </div>

                                <div class="text-muted small">
                                    O formulário poderá continuar sendo editado
                                    normalmente após a publicação.
                                </div>
                            </div>
                        </div>

                    </div>

                @endif

                @if ($form->form_anterior_id)
                    <div class="d-flex gap-2 mt-3 text-muted small">
                        <i class="bi bi-info-circle mt-1"></i>

                        <span>
                            O formulário continuará editável. Quando uma alteração
                            exigir versionamento, uma nova versão será criada
                            automaticamente.
                        </span>
                    </div>
                @endif

            </div>

            {{-- Rodapé --}}
            <div class="modal-footer bg-light border-top px-4 py-3">
                <button
                    type="button"
                    class="btn btn-light border"
                    data-bs-dismiss="modal"
                >
                    Cancelar
                </button>

                <form
                    action="{{ route('coord.forms.publicar', $form) }}"
                    method="POST"
                    class="m-0"
                >
                    @csrf

                    <button
                        type="submit"
                        class="btn btn-my-primary d-inline-flex align-items-center gap-2"
                    >
                        <i class="bi bi-send-check"></i>

                        @if ($form->form_anterior_id)
                            Publicar versão {{ $form->versao }}
                        @else
                            Publicar formulário
                        @endif
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>