<div class="modal fade" id="modalInscricaoPCD" tabindex="-1" aria-labelledby="modalInscricaoPCDLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #114048ff; color: white;">
                <h5 class="modal-title" id="modalInscricaoPCDLabel">Solicitação de Inscrição PCD</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('inscricao.pcd.store', $evento) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <p>Para solicitar sua comprovação como Pessoa com Deficiência (PCD), por favor, anexe um documento comprobatório (laudo, identificação, carteira PCD, etc.).</p>
                    <p>O arquivo deve ser no formato PDF, JPG, JPEG ou PNG e ter no máximo 5MB.</p>
                    
                    {{-- Verifica se o usuário já tem uma inscrição ou solicitação para desabilitar o formulário --}}
                    @if(auth()->check() && (auth()->user()->inscricaos()->where('evento_id', $evento->id)->exists() || \App\Models\Inscricao\InscricaoPCD::where('user_id', auth()->id())->where('evento_id', $evento->id)->exists()))
                        <div class="alert alert-warning">Você já possui solicitação para este evento.</div>
                    @else
                        <div class="mb-3">
                            <label for="comprovante" class="form-label">Anexar Comprovante</label>
                            <input class="form-control @error('comprovante') is-invalid @enderror" type="file" id="comprovante" name="comprovante" required>
                            @error('comprovante')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    {{-- Só exibe o botão de enviar se o usuário puder fazer a solicitação --}}
                    @if(!(auth()->check() && (auth()->user()->inscricaos()->where('evento_id', $evento->id)->exists() || \App\Models\Inscricao\InscricaoPCD::where('user_id', auth()->id())->where('evento_id', $evento->id)->exists())))
                        <button type="submit" class="btn btn-primary" style="background-color: #114048ff; border-color: #114048ff;">Enviar Solicitação</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
