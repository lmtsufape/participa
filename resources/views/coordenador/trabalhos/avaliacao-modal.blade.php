<div class="modal fade" id="avaliacao-{{$descricao}}-{{$trabalho->id}}" tabindex="-1" aria-labelledby="modalLabel{{$trabalho->id}}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel{{$trabalho->id}}">@if($descricao == 'corrigir')Correção @else{{ucfirst($descricao)}} @endif</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-{{$descricao}}-{{$trabalho->id}}" method="POST" action="{{route('trabalho.aprovacao', $trabalho->id)}}">
                    <input type="hidden" name="trabalho_id" value={{$trabalho->id}}>
                    <input type="hidden" name="aprovado" value="{{$valor}}">
                    @csrf
                    Tem certeza que deseja @if($descricao == 'corrigir')liberar para correção @else{{$descricao}} @endif este trabalho "{{$trabalho->titulo}}"?
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success" form="form-{{$descricao}}-{{$trabalho->id}}">Sim</button>
            </div>
        </div>
    </div>
</div>