@extends('layouts.app')
@section('main-classes', 'p-0')
@section('content')

    <div class="container mt-3 mb-5">
        <x-admin.content-header
            title="Minhas Inscrições"
            description="Acompanhe suas inscrições e consulte os detalhes de cada participação."
        />
        <form method="GET" class="row mb-4 align-items-center">
            <div class="col-md-4">
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

            <div class="col-md-4"></div>

            <div class="col-md-4">
                <select name="ordenar" class="form-select w-100" onchange="this.form.submit()">
                    <option value="data" {{ request('ordenar') === 'data' ? 'selected' : '' }}>{{ __('Ordenar por data') }}</option>
                    <option value="nome" {{ request('ordenar') === 'nome' ? 'selected' : '' }}>{{ __('Ordenar por nome') }}</option>
                </select>
            </div>
        </form>

        <div class="row">
            @if($eventos->count() != 0)
            @foreach ($eventos as $evento)
                <div class="col-md-4 mt-2 d-flex align-items-stretch">
                    <x-evento_card
                        :icone="$evento->fotoEvento ? null : $evento->icone"
                        :fotoEvento="$evento->fotoEvento"
                        :nome="$evento->nome"
                        :dataInicioFormatada="\Carbon\Carbon::parse($evento->dataInicio)->translatedFormat('l, d \d\e F')"
                        :dataFimFormatada="\Carbon\Carbon::parse($evento->dataFim)->translatedFormat('l, d \d\e F')"
                        :descricao="Str::limit(strip_tags($evento->descricao ?? ''), 120)"
                        :link="route('evento.visualizar', ['id' => $evento->id])"
                    />
                </div>
            @endforeach
            @else
            <div class="d-flex justify-content-center align-items-center flex-column py-5">
                <div class="d-flex justify-content-center align-items-center flex-column card text-center shadow-sm border-0 p-4" style="max-width: 600px;">
                    <img src="{{asset('img/iconeCalendario.png')}}" style="width: 100px; margin-bottom: 10px;"/>
                    <h2 class="fw-semibold text-dark mb-3">{{ __('Você ainda não participou ou criou nenhum evento.') }}</h2>
                    <p class="text-muted mb-0">{{ __('Explore os eventos disponíveis na plataforma, inscreva-se ou crie seu próprio evento para começar a interagir.') }}</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Paginação --}}
        <div class="mt-5 d-flex justify-content-center">
            {{ $eventos->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>

    </div>

@endsection
