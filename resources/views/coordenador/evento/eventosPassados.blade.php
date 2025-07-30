@extends('layouts.app')
@section('main-classes', 'p-0')
@section('content')

    {{-- Banner superior --}}
    <div class="position-relative mb-5">
        <img src="{{ asset('img/banner-ultimos-eventos.png') }}" alt="Últimos eventos"
             class="img-fluid w-100" style="max-height: 300px; object-fit: cover;">
        <h1 class="position-absolute top-50 start-50 translate-middle text-white fw-semibold"
            style="font-size: 2rem; text-shadow: 1px 1px 6px rgba(0, 0, 0, 0.6);">
            {{ __('Últimos eventos realizados') }}
        </h1>
    </div>

    <div class="container mb-5">
        <form method="GET" class="row mb-4 align-items-center">
            {{-- Campo de busca com botão de lupa à direita --}}
            <div class="col-md-8">
                <div class="input-group">
                    <input type="text"
                        name="busca"
                        class="form-control"
                        placeholder="{{ __('Pesquise por evento...') }}"
                        value="{{ request('busca') }}">

                    <button type="submit" class="btn bg-white border-start-0">
                        <img src="{{ asset('img/icons/search.svg') }}" alt="Buscar" width="20px">
                    </button>
                </div>
            </div>

            {{-- Ordenar por (recarrega ao mudar) --}}
            <div class="col-md-4">
                <select name="ordenar" class="form-select w-100" onchange="this.form.submit()">
                    <option value="data" {{ request('ordenar') === 'data' ? 'selected' : '' }}>{{ __('Ordenar por data') }}</option>
                    <option value="nome" {{ request('ordenar') === 'nome' ? 'selected' : '' }}>{{ __('Ordenar por nome') }}</option>
                </select>
            </div>
        </form>

        {{-- Mensagem de erro --}}


        {{-- Lista de eventos --}}
        <div class="row">
            @foreach ($eventosPassados as $evento)
                <div class="col-md-4 mt-2 d-flex align-items-stretch">
                    <x-evento_card
                        :icone="$evento->icone"
                        :fotoEvento="$evento->fotoEvento"
                        :nome="$evento->nome"
                        :dataInicioFormatada="\Carbon\Carbon::parse($evento->dataInicio)->translatedFormat('l, d \d\e F \d\e Y')"
                        :dataFimFormatada="\Carbon\Carbon::parse($evento->dataFim)->translatedFormat('l, d \d\e F \d\e Y')"
                        :descricao="Str::limit(strip_tags($evento->descricao ?? ''), 120)"
                        :link="route('evento.visualizar', ['id' => $evento->id])"
                    />
                </div>
            @endforeach
        </div>

        {{-- Paginação --}}
        <div class="mt-5 d-flex justify-content-center">
            {{ $eventosPassados->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>

@endsection
