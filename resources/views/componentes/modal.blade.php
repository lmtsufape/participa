<!-- Button trigger modal -->

  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#email_lembrar_{{$revisor->id}}">
    Enviar e-mail
  </button>

  <!-- Modal -->
  <div class="modal fade" id="email_lembrar_{{$revisor->id}}" tabindex="-1" aria-labelledby="#email_lembrar_{{$revisor->id}}_label" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="email_lembrar_{{$revisor->id}}_label">Enviar E-mail</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row ">
                <div class="col-sm-12">
                    <h6 class="card-subtitle mb-2 text-muted">Edite o texto do email para o envio</h6>
                    <br>
                    <h6 class="card-subtitle mb-2 text-muted">Para: {{$revisor->email}}</h6>
                    <form id="form-enviar-email-revisor-{{$revisor->id}}" method="POST" action="{{route('revisor.email')}}">
                      @csrf
                      <p class="card-text">
                        <input type="hidden" name="evento_id" value="{{$evento->id}}">
                        <input type="hidden" name="revisor_id" value="{{$revisor->id}}">
                        <div class="row ">
                          <div class="col-sm-12">
                            <label for="assunto" class="col-form-label">{{ __('Assunto') }}</label>
                            <input id="assunto" type="text" class="form-control  @error('assunto') is-invalid @enderror" name="assunto" value="{{old('assunto', "Avaliador do evento")}}" required autocomplete="nome" autofocus>
                            @error('assunto')
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror

                            <label for="texto_do_email" class="col-form-label">{{ __('Texto') }}</label>
                            <textarea class="form-control" id="texto_do_email" name="texto_do_email" rows="3">{{old('texto_do_email', "Convidamos vossa senhoria, para ser avaliador do evento " . $evento->nome . ".")}}</textarea>

                            @error('texto_do_email')
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                          </div>
                        </div>
                      </p>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" >Fechar</button>
          <button type="submit" class="btn btn-primary" form="form-enviar-email-revisor-{{$revisor->id}}" >
              {{ __('Finalizar') }}
          </button>
        </div>
      </div>
    </div>
  </div>
