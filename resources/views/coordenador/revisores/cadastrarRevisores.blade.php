@extends('coordenador.detalhesEvento')

@section('menu')

    <!-- Revisores -->
    <div id="divCadastrarRevisores" style="display: block">

        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Cadastrar Revisores</h1>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Revisores</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Cadastre um novo revisor no seu evento</h6>
                        <form method="POST" action="{{route('revisor.store')}}">
                            @csrf
                            <p class="card-text">
                                <input type="hidden" name="eventoId" value="{{$evento->id}}">
                                <div class="row justify-content-center">
                                    {{-- <div class="col-sm-3">
                                        <label for="nomeRevisor" class="col-form-label">{{ __('Nome do Revisor') }}</label>
                                        <input id="nomeRevisor" type="text" class="form-control apenasLetras @error('nomeRevisor') is-invalid @enderror" name="nomeRevisor" value="{{ old('nomeRevisor') }}" required autocomplete="nomeRevisor" autofocus>

                                        @error('nomeRevisor')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div> --}}
                                    <div class="col-sm-4">
                                        <label for="emailRevisor" class="col-form-label">{{ __('Email do Revisor') }}</label>
                                        <input id="emailRevisor" type="text" class="form-control @error('emailRevisor') is-invalid @enderror" name="emailRevisor" value="{{ old('emailRevisor') }}" required autocomplete="emailRevisor" autofocus>

                                        @error('emailRevisor')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="areaRevisor" class="col-form-label">{{ __('Área') }}</label>
                                        <select class="form-control @error('areaRevisor') is-invalid @enderror" id="areaRevisor" name="areaRevisor">
                                            <option value="" disabled selected hidden>-- Área --</option>
                                            @foreach($areas as $area)
                                            <option value="{{$area->id}}">{{$area->nome}}</option>
                                            @endforeach
                                        </select>

                                        @error('areaRevisor')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="modalidadeRevisor" class="col-form-label">{{ __('Modalidade') }}</label>
                                        <select class="form-control @error('modalidadeRevisor') is-invalid @enderror" id="modalidadeRevisor" name="modalidadeRevisor">
                                            <option value="" disabled selected hidden>-- Modalidade --</option>
                                            @foreach($modalidades as $modalidade)
                                            <option value="{{$modalidade->id}}">{{$modalidade->nome}}</option>
                                            @endforeach
                                        </select>

                                        @error('modalidadeRevisor')
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
                </div>
            </div>
        </div>




    </div>

@endsection
