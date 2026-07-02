@extends('layouts.app')
@section('sidebar')
@endsection
@section('content')

<div class="container">
    <x-admin.content-header
        title="Listar Formulários"
        description="Gerencie os formulários utilizados na avaliação dos trabalhos desta modalidade."
        :href="route('coord.atribuir.form', [
            'evento_id' => $evento->id,
            'modalidade_id' => $modalidade->id
        ])"
        button-text="Novo formulário"
    />
    <p class="pt-0 text-muted">
         Modalidade:
         <strong class="text-my-primary">
             {{ $modalidade->nome }}
         </strong>
    </p>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Título do Formulário</th>
                <th scope="col" class="text-center">Opções</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($forms as $form)
                <tr>
                    <td>{{ $form->titulo }}</td>
                    <td>
                        <td>
                            <form action="{{ route('coord.visualizar.form') }}" method="get">
                                <input type="hidden" name="evento_id" value="{{ $evento->id }}">
                                <input type="hidden" name="modalidade_id" value="{{ $form->modalidade->id }}">
                                <button type="submit" class="btn btn-secondary">
                                    Visualizar formulário
                                </button>
                            </form>
                        </td>
                        <td>
                            <input type="hidden" name="modalidade_id" value="{{ $form->modalidade->id }}">
                            <a class="btn btn-secondary" href=" {{ route('coord.respostasToPdf', $form->modalidade) }} ">
                                Ver respostas
                            </a>
                        </td>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
