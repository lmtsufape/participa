@extends('layouts.app')

@section('content')

<div class="container position-relative">

    {{-- titulo da página --}}
    <div class="row justify-content-center titulo-detalhes">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-10">
                    <h1>{{ __('Eventos como Coordenador de Eixo') }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row cards-eventos-index">
        @foreach ($eventos as $evento)
            @if ($evento->deletado == false)
                @can('isPublishOrIsCoordenadorOrCoordenadorDasComissoes', $evento)
                <div class="col-md-4">
                    <div class="card">
                        @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en' && isset($evento->fotoEvento_en))
                            <img src="{{asset('storage/'.$evento->fotoEvento_en)}}" class="card-img-top" alt="...">
                        @elseif(isset($evento->fotoEvento))
                            <img src="{{asset('storage/'.$evento->fotoEvento)}}" class="card-img-top" alt="...">
                        @else
                            <img src="{{asset('img/colorscheme.png')}}" class="card-img-top" alt="..." style="height: 150px;">
                        @endif
                        <div class="card-body">
                            <div class="card-title text-justify">
                                <h3>
                                    @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                                        {{$evento->nome_en}}
                                    @else
                                        {{$evento->nome}}
                                    @endif
                                </h3>
                                <p>
                                    @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                                        {{$evento->descricao_en}}
                                    @else
                                        {{$evento->descricao}}
                                    @endif
                                </p>

                            </div>
                            <div class="card-text">
                                <p class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-event" viewBox="0 0 16 16">
                                    <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                                  </svg> {{ \Carbon\Carbon::parse($evento->dataFim)->locale(Session::get('idiomaAtual', 'pt'))->translatedFormat('l, d F') }}</p>
                                <p>
                                    <div class="row justify-content-center">
                                        <div class="col-sm-12">
                                            <img src="{{ asset('/img/icons/location_pointer.png') }}" alt="" width="18px" height="auto">
                                            {{$evento->endereco->rua}}, {{$evento->endereco->numero}} - {{$evento->endereco->cidade}} / {{$evento->endereco->uf}}.
                                        </div>
                                    </div>
                                </p>
                                <div class="row">
                                    <div class="col-md-4">
                                        <a class="d-flex flex-column align-items-center text-success text-decoration-none" href="{{route('evento.visualizar',['id'=>$evento->id])}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                                                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                                            </svg>
                                            <span style="font-size: 10px"><strong>{{__('Visualizar evento')}}</strong></span>
                                        </a>
                                    </div>
                                    <div class="col-md-4">
                                        <a class="d-flex flex-column align-items-center text-secondary text-decoration-none" href="{{ route('coord.detalhesEvento', ['eventoId' => $evento->id]) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
                                                <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
                                            </svg>
                                            <span style="font-size: 10px"><strong>{{__('Configurar evento')}}</strong></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan
            @endif
        @endforeach
    </div>
</div>

@endsection