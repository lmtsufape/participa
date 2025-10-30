<div>
    <div class="modal fade" id="appModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#114048ff;color:white;">
                    <h5 class="modal-title">{{ $htmlTitle }}</h5>
                    <button type="button" class="btn-close btn-close-white" aria-label="Close"
                        wire:click="$dispatch('close-modal')"></button>
                </div>

                <div class="modal-body">
                    @if ($open && $child)
                        <div wire:key="modal-shell-{{ $instance }}">
                            @livewire($child, $params, key('modal-child-'.$instance.'-'.md5($child . '|' . json_encode($params))))
                        </div>
                    @else
                        <div class="text-center text-muted py-4">Carregando…</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('bootstrap-modal:show', ({
                id
            }) => {
                const el = document.getElementById(id);
                const m = bootstrap.Modal.getOrCreateInstance(el, {
                    backdrop: true,
                    keyboard: true
                });
                m.show();
            });
            Livewire.on('bootstrap-modal:hide', ({
                id
            }) => {
                const el = document.getElementById(id);
                const m = bootstrap.Modal.getInstance(el);
                if (m) m.hide();
            });
        });
    </script>
</div>