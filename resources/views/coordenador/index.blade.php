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
        <x-ui.content-header
            title="{{__('Meus Eventos')}}"
            description="Visualize e gerencie os eventos que você criou."
            :href="route('evento.criar')"
            button-text="{{ __('Novo Evento') }}"
        />

        <div class="row g-4">
            @foreach ($eventos as $evento)
                @if (! $evento->deletado)
                    @can('isCoordenadorOrCoordenadorDasComissoes', $evento)
                        <div class="col-12 col-md-6 col-xl-4">
                            <x-eventos.card :evento="$evento"/>
                        </div>
                    @endcan
                @endif
            @endforeach
        </div>
        <div class="d-flex justify-content-center pt-4">
            {{ $eventos->links('pagination::bootstrap-4') }}
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
