 <!-- Button trigger modal -->
 <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal{{$area->id}}">
    Atribuir
  </button>

  <!-- Modal -->
  <div class="modal fade" id="Modal{{$area->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        {{-- <form action="{{route('atribuir.revisor.lote')}}" method="post">
            @csrf
            <div class="input-group mb-3">
                <select name="revisor" class="custom-select" id="inputGroupSelect{{$area->id}}">

                @if (!$revisores->contains($area->id))
                    @foreach ($revisores as $revisor)
                        <option value="{{$revisor->id}}" >{{$revisor->user->email}}</option>
                    @endforeach
                @else
                    <option>Não há revisores</option>
                @endif

                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
               <button type="submit" class="btn btn-primary" id="addRevisorTrabalho">Adicionar Revisor</button>
            </div>
        </form> --}}
        <form action="{{route('atribuir.revisor.lote')}}" method="post">
            @csrf
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Fechar') }}</button>
                      <button type="submit" class="btn btn-primary">Salvar</button>
                  </div>
                  <select class="custom-select" id="inputGroupSelect03" aria-label="Example select with button addon">
                    <option selected>Choose...</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                  </select>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
