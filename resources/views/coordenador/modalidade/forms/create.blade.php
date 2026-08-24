@extends('layouts.app')
@section('sidebar')


@endsection
@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                <div>
                    <h3 class="mb-1 fw-bold">
                        Adicionar Formulário
                    </h3>

                    <p class="mb-0 text-muted">
                        Modalidade:
                        <strong class="text-my-primary">
                            {{ $modalidade->nome }}
                        </strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <form action="{{route('coord.salvar.form')}}" method="post">
        @csrf
        <input type="hidden" name="modalidade_id" value="{{ $modalidade->id }}">
        <input type="hidden" name="evento_id" value="{{ $evento->id }}">
        @include('coordenador.modalidade.forms._form', [
           'form' => null,
        ])
        <button type="submit" class="btn btn-my-primary col-12 mt-1">
            Salvar
        </button>
    </form>
</div>
@endsection
