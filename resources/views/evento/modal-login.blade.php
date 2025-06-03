<div class="modal fade" id="modalLoginPrompt" tabindex="-1" aria-labelledby="modalLoginPromptLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLoginPromptLabel">{{ __('Atenção') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Fechar') }}"></button>
                </div>
                <div class="modal-body">
                    {{ __('Você precisa estar logado para poder submeter um trabalho.') }}
                </div>
                <div class="modal-footer">
                    <a href="{{ route('login') }}" class="btn btn-primary">{{ __('Fazer Login') }}</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                </div>
            </div>
        </div>
    </div>
