@props(['trabalho'])
<div class="modal fade" id="modalExcluirTrabalho_{{$trabalho->id}}" tabindex="-1" aria-labelledby="modalExcluirTrabalho_{{$trabalho->id}}Label" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #114048ff; color: white;">
          <h5 class="modal-title" id="modalExcluirTrabalho_{{$trabalho->id}}Label">Excluir {{$trabalho->id}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="formExcluirTrab{{$trabalho->id}}" action="{{route('excluir.trabalho', ['id' => $trabalho->id])}}" method="POST">
            @csrf
            <p>
              {{ __('Tem certeza que deseja excluir esse trabalho?') }}
            </p>
            <small>{{ __('Ninguém poderá ver seu trabalho após a exclusão.') }}</small>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Não') }}</button>
          <button type="submit" class="btn btn-primary" form="formExcluirTrab{{$trabalho->id}}">Sim</button>
        </div>
      </div>
    </div>
</div>
