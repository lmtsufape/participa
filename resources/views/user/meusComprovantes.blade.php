@extends('layouts.app')
@section('main-classes', 'p-0')
@section('content')
    <br><br>

    <div class="position-relative mb-5">
        <h1 class="position-absolute top-50 start-50 translate-middle fw-semibold"
            style="font-size: 2rem; color: #034652;">
            @lang('Meus Comprovantes')
        </h1>
    </div>

    <div class="container mb-5">
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
                    >
                    @php
                        $inscricaoPaga = $evento->inscricaos
                            ->where('user_id', auth()->id())
                            ->filter(function($i){ return $i->pagamento && $i->pagamento->status === 'approved'; })
                            ->first();
                    @endphp
                    @if($inscricaoPaga)
                        <div class="mt-2 w-100">
                            <a href="{{ route('inscricao.recibo', ['inscricao' => $inscricaoPaga->id]) }}" class="btn btn-sm btn-success w-100">
                                {{ __('Comprovante') }}
                            </a>
                        </div>
                    @endif
                    </x-evento_card>
                </div>
            @endforeach
            @else 
            <div class="d-flex justify-content-center align-items-center flex-column py-5">
                <div class="d-flex justify-content-center align-items-center flex-column card text-center shadow-sm border-0 p-4" style="max-width: 600px;">
                    <img src="{{asset('img/iconeCalendario.png')}}" style="width: 100px; margin-bottom: 10px;"/>
                    <h2 class="fw-semibold text-dark mb-3">@lang('Você ainda não possui comprovantes de inscrição.')</h2>
                    <p class="text-muted mb-0">@lang('Os comprovantes ficarão disponíveis após a confirmação do pagamento das suas inscrições em eventos.')</p>
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
