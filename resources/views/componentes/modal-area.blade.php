<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Cadastrar área
  </button>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ __('Cadastrar Área') }}</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <h6 class="card-subtitle mb-2 text-muted">{{ __('Cadastre uma nova área para o seu evento') }}</h6>
                    <form method="POST" action="{{route('area.store')}}">
                        @csrf
                        <input type="hidden" name="eventoId" value="{{$evento->id}}">
                    <p class="card-text">
                        <div class="row justify-content-center">
                            <div class="col-sm-12">
                                <label for="nome" class="col-form-label">{{ __('Nome da Área') }}*</label>
                                <input id="nome" type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" value="{{ old('nome') }}" required autocomplete="nome" autofocus>

                                @error('nome')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-sm-12">
                                <label for="resumo" class="col-form-label">{{__('Resumo')}}</label>
                                <textarea id="resumo" class="form-control @error('resumo') is-invalid @enderror" name="resumo" rows="3">{{ old('resumo') }}</textarea>                            </div>
                            </div>
                            @if ($evento->is_multilingual)
                            <div class="col-sm-12">
                                <label for="nome_en" class="col-form-label">{{ __('Nome da Área (Inglês)') }}*</label>
                                <input id="nome_en" type="text" class="form-control @error('nome_en') is-invalid @enderror" name="nome_en" value="{{ old('nome_en') }}" autocomplete="nome_en" required>

                            </div>
                            <div class="col-sm-12">
                                <label for="resumo_en" class="col-form-label">{{__('Resumo (Inglês)')}}</label>
                                <textarea id="resumo_en" class="form-control @error('resumo_en') is-invalid @enderror" name="resumo_en" rows="3">{{ old('resumo_en') }}</textarea>
                            </div>
                            <div class="col-sm-12">
                                <label for="nome_es" class="col-form-label">{{ __('Nome da Área (Espanhol)') }}*</label>
                                <input id="nome_es" type="text" class="form-control @error('nome_es') is-invalid @enderror" name="nome_es" value="{{ old('nome_es') }}" autocomplete="nome_es" required>


                            </div>
                            <div class="col-sm-12">
                                <label for="resumo_es" class="col-form-label">{{__('Resumo (Espanhol)')}}</label>
                                <textarea id="resumo_es" class="form-control @error('resumo_es') is-invalid @enderror" name="resumo_es" rows="3">{{ old('resumo_es') }}</textarea>
                            </div>
                            @endif
                    </p>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Fechar') }}</button>
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
