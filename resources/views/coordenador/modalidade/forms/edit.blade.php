@extends('layouts.app')
@section('sidebar')
@endsection
@section('content')

    <div class="container">
        <div class="row mb-4">
            <div class="col-md-5">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                    <div>
                        <h3 class="mb-1 fw-bold">
                            Editar Formulário
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
            <div class="col-md-7">
                @include('coordenador.modalidade.forms.mode-switch', ['form' => $form, 'active' => "edicao"])
            </div>
        </div>
        <form method="POST" action="{{route('coord.update.form', $form->id)}}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            @include('coordenador.modalidade.forms._form', [
                'form' => $form,
            ])

             <button type="submit" class="btn btn-my-primary col-12 mt-1">
            Salvar
        </button>
        </form>

    </div>
@endsection
