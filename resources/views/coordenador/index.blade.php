@extends('layouts.app')


@section('content')

    <style>
        .event-card-img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
    </style>

<div class="container">

    {{-- titulo da página --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h1>{{__('Meus Eventos')}}</h1>
                </div>
                 <div>
                    <a href="{{route('evento.criar')}}" class="btn btn-primary">{{__('Novo Evento')}}</a>
                </div>

    </div>
    <div class="row">
        @php
            use Illuminate\Support\Str;
        @endphp

        @foreach ($eventos as $evento)
            @if (! $evento->deletado)
                @can('isCoordenadorOrCoordenadorDasComissoes', $evento)
                    <div class="col-md-4">
                        <div class="card">

                            {{-- IMAGEM DO EVENTO --}}
                            @if(
                                $evento->is_multilingual
                                && Session::get('idiomaAtual') === 'en'
                                && ! empty($evento->fotoEvento_en)
                            )
                                <img
                                    src="{{ asset('storage/'.$evento->fotoEvento_en) }}"
                                    class="card-img-top event-card-img"
                                    alt="{{ $evento->nome_en }}"
                                >
                            @elseif(! empty($evento->fotoEvento))
                                <img
                                    src="{{ asset('storage/'.$evento->fotoEvento) }}"
                                    class="card-img-top event-card-img"
                                    alt="{{ $evento->nome }}"
                                >
                            @else
                                <img
                                    src="{{ asset('img/colorscheme.png') }}"
                                    class="card-img-top event-card-img"
                                    alt="Imagem padrão"
                                    style="height: 150px;"
                                >
                            @endif

                            <div class="card-body">
                                {{-- TÍTULO E DESCRIÇÃO COM STR::limit --}}
                                @php
                                    $titulo = ($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                                              ? $evento->nome_en
                                              : $evento->nome;
                                    $descricao = ($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                                                 ? $evento->descricao_en
                                                 : $evento->descricao;
                                @endphp

                                <div class="card-title text-justify">
                                    <h5>{{ Str::limit($titulo, 60, '...') }}</h5>
                                    <p>{{ Str::limit(strip_tags($descricao), 100, '...') }}</p>
                                </div>

                                {{-- SEU CÓDIGO DE DATAS E ENDEREÇO AQUI (sem alterações) --}}

                                <div class="row">
                                    <div class="col-md-4">
                                        <a
                                            class="d-flex flex-column align-items-center text-success text-decoration-none"
                                            href="{{ route('evento.visualizar', ['id' => $evento->id]) }}"
                                        >
                                            {{-- ÍCONE DE VISUALIZAR 20×20 --}}
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 width="20" height="20"
                                                 fill="currentColor"
                                                 class="bi bi-eye-fill"
                                                 viewBox="0 0 16 16"
                                            >
                                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                                                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8
                                                 m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                                            </svg>
                                            <span style="font-size:12px">
                                        <strong>{{ __('Visualizar evento') }}</strong>
                                    </span>
                                        </a>
                                    </div>
                                    <div class="col-md-4">
                                        <a
                                            class="d-flex flex-column align-items-center text-secondary text-decoration-none"
                                            href="{{ route('coord.detalhesEvento', ['eventoId' => $evento->id]) }}"
                                        >
                                            {{-- ÍCONE DE CONFIGURAR 20×20 --}}
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 width="20" height="20"
                                                 fill="currentColor"
                                                 class="bi bi-gear-fill"
                                                 viewBox="0 0 16 16"
                                            >
                                                <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34
                                                 a1.464 1.464 0 0 1-2.105.872l-.31-.17
                                                 c-1.283-.698-2.686.705-1.987 1.987l.169.311
                                                 c.446.82.023 1.841-.872 2.105l-.34.1
                                                 c-1.4.413-1.4 2.397 0 2.81l.34.1
                                                 a1.464 1.464 0 0 1 .872 2.105l-.17.31
                                                 c-.698 1.283.705 2.686 1.987 1.987l.311-.169
                                                 a1.464 1.464 0 0 1 2.105.872l.1.34
                                                 c.413 1.4 2.397 1.4 2.81 0l.1-.34
                                                 a1.464 1.464 0 0 1 2.105-.872l.31.17
                                                 c1.283.698 2.686-.705 1.987-1.987l-.169-.311
                                                 a1.464 1.464 0 0 1 .872-2.105l.34-.1
                                                 c1.4-.413 1.4-2.397 0-2.81l-.34-.1
                                                 a1.464 1.464 0 0 1-.872-2.105l.17-.31
                                                 c.698-1.283-.705-2.686-1.987-1.987l-.311.169
                                                 a1.464 1.464 0 0 1-2.105-.872z
                                                 M8 10.93a2.929 2.929 0 1 1 0-5.86
                                                 2.929 2.929 0 0 1 0 5.858z"/>
                                            </svg>
                                            <span style="font-size:12px">
                                        <strong>{{ __('Configurar evento') }}</strong>
                                    </span>
                                        </a>
                                    </div>

                                    @can('isCriador', $evento)
                                        {{-- botão de deletar --}}
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
            @endif
        @endforeach
    </div>
    @foreach ($eventos as $evento)
        @if ($evento->deletado == false)
            <!-- Modal de exclusão do evento -->
            <x-modal-excluir-evento :evento="$evento"/>
            <!-- fim do modal -->
        @endif
    @endforeach

</div>

@endsection
