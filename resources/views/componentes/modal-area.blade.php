<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Cadastrar área
  </button>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Cadastrar Área</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <h6 class="card-subtitle mb-2 text-muted">Cadastre uma nova área para o seu evento</h6>
                    <form method="POST" action="{{route('area.store')}}">
                        @csrf
                        <input type="hidden" name="eventoId" value="{{$evento->id}}">
                    <p class="card-text">
                        <div class="row justify-content-center">
                            <div class="col-sm-12">
                                <label for="nome" class="col-form-label">{{ __('Nome da Área') }}</label>
                                <input id="nome" type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" value="{{ old('nome') }}" required autocomplete="nome" autofocus>

                                @error('nome')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </p>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary" >
                            {{ __('Finalizar') }}
                        </button>
                    </div>

                    </form>
                </div>
            </div>
        </div>

      </div>
    </div>
  </div>
