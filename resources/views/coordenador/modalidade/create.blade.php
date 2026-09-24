@extends('layouts.app')
@section('sidebar')

@endsection
@section('content')

    <div class="container">

        <x-admin.content-header
            title="Cadastrar Modalidade"
            description="Configure as informações, prazos, regras de submissão e documentos da modalidade."
        />

        <form method="POST" action="{{route('modalidade.store', ['evento_id' => $evento->id])}}" enctype="multipart/form-data">
            @csrf

            @include('coordenador.modalidade._form')

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary" style="width:100%">
                        {{ __('Finalizar') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
