@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">
                        <img src="{{ asset('img/icons/user-times-solid.svg') }}" alt="Inválido" style="width: 20px; height: 20px; margin-right: 8px; filter: brightness(0) invert(1);"> Recibo Inválido
                    </h4>
                </div>

                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="alert alert-danger">
                            <img src="{{ asset('img/icons/question-circle-solid.svg') }}" alt="Atenção" style="width: 16px; height: 16px; margin-right: 5px;">
                            <strong>Atenção!</strong><br>
                            O código de validação informado não foi encontrado em nossa base de dados.
                        </div>
                    </div>

                                         <div class="row">
                         <div class="col-md-12">
                             <h5 class="mb-3"><img src="{{ asset('img/icons/info-circle-solid.svg') }}" alt="Informação" style="width: 16px; height: 16px; margin-right: 5px;"> Possíveis Motivos:</h5>
                             <ul class="list-group list-group-flush">
                                 <li class="list-group-item py-2">
                                     <img src="{{ asset('img/icons/user-times-solid.svg') }}" alt="Erro" style="width: 14px; height: 14px; margin-right: 8px; color: #dc3545;"> O código foi digitado incorretamente
                                 </li>
                                 <li class="list-group-item py-2">
                                     <img src="{{ asset('img/icons/user-times-solid.svg') }}" alt="Erro" style="width: 14px; height: 14px; margin-right: 8px; color: #dc3545;"> O documento não foi emitido por este sistema
                                 </li>
                                 <li class="list-group-item py-2">
                                     <img src="{{ asset('img/icons/user-times-solid.svg') }}" alt="Erro" style="width: 14px; height: 14px; margin-right: 8px; color: #dc3545;"> O recibo pode ter sido cancelado ou excluído
                                 </li>
                             </ul>
                         </div>
                     </div>

                    <div class="text-center mt-4">
                        <div class="alert alert-warning">
                            <img src="{{ asset('img/icons/info-circle-solid.svg') }}" alt="Importante" style="width: 16px; height: 16px; margin-right: 5px;">
                            <strong>Importante:</strong> Se você acredita que este é um erro, entre em contato com a Associação Brasileira de Agroecologia (ABA).
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <img src="{{ asset('img/icons/arrow.svg') }}" alt="Voltar" style="width: 16px; height: 16px; margin-right: 5px; transform: rotate(180deg);"> Voltar ao Início
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
