@extends('layouts.app')

@section('content')
    <div class="container position-relative">

        {{-- titulo da página --}}

        <div class="text-center">
            <h1>Próximos eventos a serem realizados</h1>
        </div>



        <div class="container-fluid content-row">
            <div class="row">
                @foreach ($proximosEventos as $evento)
                    <div class="col-md-4 mt-2 d-flex align-items-stretch">
                        <div class="card">
                            @if ($evento->icone != null)
                                <img src="{{ asset('storage/' . $evento->icone) }}" alt="imagem evento" width="100%"
                                     height="150px">
                            @elseif($evento->fotoEvento != null)
                                <img src="{{ asset('storage/' . $evento->fotoEvento) }}" alt="imagem evento" width="100%"
                                     height="150px">
                            @else
                                <img src="{{ asset('img/colorscheme.png') }}" alt="" width="100%" height="150px">
                            @endif
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h5 class="card-title">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <a href="{{ route('evento.visualizar', ['id' => $evento->id]) }}"
                                                       style="text-decoration: inherit;">
                                                        {{ $evento->nome }}
                                                    </a>
                                                </div>
                                            </div>

                                        </h5>
                                    </div>
                                </div>
                                <div>
                                    <p class="card-text">
                                        <img src="{{ asset('/img/icons/calendar.png') }}" alt="" width="20px;"
                                             style="position: relative; top: -2px;">
                                        {{ date('d/m/Y', strtotime($evento->dataInicio)) }} -
                                        {{ date('d/m/Y', strtotime($evento->dataFim)) }}<br>
                                        {{-- <strong>Submissão:</strong> {{date('d/m/Y',strtotime($evento->inicioSubmissao))}} - {{date('d/m/Y',strtotime($evento->fimSubmissao))}}<br>
                                        <strong>Revisão:</strong> {{date('d/m/Y',strtotime($evento->inicioRevisao))}} - {{date('d/m/Y',strtotime($evento->fimRevisao))}}<br> --}}
                                    </p>
                                    <p>
                                    <div class="row justify-content-center">
                                        <div class="col-sm-12">
                                            <img src="{{ asset('/img/icons/location_pointer.png') }}" alt=""
                                                 width="18px" height="auto">
                                            {{ $evento->endereco->rua }}, {{ $evento->endereco->numero }} -
                                            {{ $evento->endereco->cidade }} / {{ $evento->endereco->uf }}.
                                        </div>
                                    </div>
                                    </p>
                                    <div class="row col-md-12">
                                        <div class="row col-md-12">
                                            <a href="{{ route('evento.visualizar', ['id' => $evento->id]) }}">
                                                <i class="far fa-eye" style="color: black"></i>&nbsp;&nbsp;Visualizar evento
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
@endsection
