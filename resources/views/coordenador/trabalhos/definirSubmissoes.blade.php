@extends('coordenador.detalhesEvento')

@section('menu')
    <!-- Definir Submissões -->
    <div id="divDefinirSubmissoes" style="display: block">

        <div class="row titulo-detalhes">
            <div class="col-sm-10">
                <h1 class="">Definir Submissões</h1>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Definir Submissões do Trabalho</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Informe o número de trabalhos que cada autor poderá enviar e o número de trabalhos em que um usuário poderá ser um coautor</h6>
                        <form method="POST" action="{{route('trabalho.numTrabalhos')}}">
                        @csrf
                        <p class="card-text">
                            <input type="hidden" name="eventoId" value="{{$evento->id}}">

                            <div class="row justify-content-center">
                                <div class="col-sm-12">
                                    <label for="trabalhosPorAutor" class="col-form-label">{{ __('Número de trabalhos por Autor') }}</label>
                                    <input id="trabalhosPorAutor" type="text" class="form-control @error('trabalhosPorAutor') is-invalid @enderror" name="trabalhosPorAutor" value="@if ($evento->numMaxTrabalhos != null){{$evento->numMaxTrabalhos}}@else{{old('trabalhosPorAutor')}}@endif" required autocomplete="trabalhosPorAutor" autofocus>

                                    @error('trabalhosPorAutor')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                            </div>{{-- end row--}}

                            <div class="row justify-content-center">
                                <div class="col-sm-12">
                                    <label for="numCoautor" class="col-form-label">{{ __('Número de trabalhos como Coautor') }}</label>
                                    <input id="numCoautor" type="text" class="form-control @error('numCoautor') is-invalid @enderror" name="numCoautor" value="@if ($evento->numMaxTrabalhos != null){{$evento->numMaxCoautores}}@else{{old('numCoautor')}}@endif" required autocomplete="numCoautor" autofocus>

                                    @error('numCoautor')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                            </div>{{-- end row--}}

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
        @can('isCoordenador', $evento)
            <div class="row justify-content-center">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Logo Evento</h5>
                            <h6 class="card-subtitle mb-2 text-muted">Modifique a foto do evento aqui.</h6>
                            <form method="POST" action="{{route('evento.setFotoEvento')}}" enctype="multipart/form-data">
                            @csrf
                            <p class="card-text">
                                <input type="hidden" name="eventoId" value="{{$evento->id}}">

                                <div class="row justify-content-center">
                                    <div class="col-sm-12">
                                    <label for="fotoEvento">Logo</label>
                                    <input type="file" class="form-control-file @error('fotoEvento') is-invalid @enderror" name="fotoEvento" value="{{ old('fotoEvento') }}" id="fotoEvento">
                                    @error('fotoEvento')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    </div>

                                </div>{{-- end row--}}
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
        @endcan
    </div><!-- Definir Submissões -->

@endsection
