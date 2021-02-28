<!-- Button trigger modal -->
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
    Enviar e-mail
  </button>
  
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Enviar E-mail</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row ">
                <div class="col-sm-12">                    
                    <h6 class="card-subtitle mb-2 text-muted">Edite o texto do email para o envio</h6>
                    <br>
                    <h6 class="card-subtitle mb-2 text-muted">Para: {{$email}}</h6>
                    <form method="POST" action="{{route('area.store')}}">
                        @csrf
                    <p class="card-text">
                        <input type="hidden" name="eventoId" value="{{$evento->id}}">
                        <div class="row ">
                            <div class="col-sm-12">
                                <label for="nome" class="col-form-label">{{ __('Assunto') }}</label>
                                <input id="nome" type="text" value="Revisor de evento" class="form-control  @error('nome') is-invalid @enderror" name="nome" value="{{ old('nome') }}" required autocomplete="nome" autofocus>

                                <label for="nome" class="col-form-label">{{ __('Texto') }}</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3">Convidamos vossa senhoria, para ser revisor do evento {{$evento->nome}}.
                                </textarea>

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