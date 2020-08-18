@extends('coordenador.detalhesEvento')

@section('menu')

    <div id="divCadastrarCriterio" class="comissao">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Cadastrar Critério</h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Novo Critério</h5>
                      <h6 class="card-subtitle mb-2 text-muted">Cadastre um novo critério por modalidade</h6>
                      <form action="{{route('cadastrar.criterio')}}" method="POST">
                        @csrf
                        <input type="hidden" name="eventoId" value="{{ $evento->id ?? '' }}">
                        <p class="card-text">
                            <div class="row justify-content-right">
                                <div class="col-sm-6">
                                    <label for="modalidade" class="col-form-label">{{ __('Escolha a Modalidade') }}</label>
                                    <select class="form-control @error('modalidade') is-invalid @enderror" id="modalidade" name="modalidade">
                                        <option value="" disabled selected hidden>-- Modalidade --</option>
                                        @foreach($modalidades as $modalidade)
                                        <option value="{{$modalidade->id}}">{{$modalidade->nome}}</option>
                                        @endforeach
                                    </select>

                                    @error('modalidade')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-sm-12">
                                  <div id="criterios">

                                  </div>
                                  <a href="#" class="btn btn-primary" id="addCriterio" style="width:100%;margin-top:10px">Novo</a>
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
                  </div>{{-- end card--}}
            </div>
        </div>
    </div>{{-- End cadastrar Comissão --}}

@endsection
