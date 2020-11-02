@extends('coordenador.detalhesEvento')

@section('menu')

    <!-- Área -->
    <div id="divCadastrarAreas" style="display: block">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Cadastrar Áreas</h1>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-5">
                <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Nova Área</h5>
                      <h6 class="card-subtitle mb-2 text-muted">Cadastre uma nova área para o seu evento</h6>
                      <form method="POST" action="{{route('area.store')}}">
                          @csrf
                        <p class="card-text">
                                <input type="hidden" name="eventoId" value="{{$evento->id}}">
                                <div class="row justify-content-center">
                                    <div class="col-sm-12">
                                        <label for="nome" class="col-form-label">{{ __('Nome da Área') }}</label>
                                        <input id="nome" type="text" class="form-control apenasLetras @error('nome') is-invalid @enderror" name="nome" value="{{ old('nome') }}" required autocomplete="nome" autofocus>

                                        @error('nome')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </p>
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary" style="width:100%">
                                        {{ __('Finalizar') }}
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                  </div>{{-- End card--}}
            </div>
        </div>
    </div>

@endsection
