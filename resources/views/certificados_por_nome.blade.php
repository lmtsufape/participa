@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">{{ __('Resultados da Busca') }}</h3>
                    <a href="{{ route('validarCertificado') }}" class="btn btn-sm btn-light">
                        {{ __('Nova Busca') }}
                    </a>
                </div>
                <div class="card-body">
                    @if($usuarios->isEmpty())
                        <div class="alert alert-warning" role="alert">
                            <h5 class="alert-heading">{{ __('Nenhum resultado encontrado') }}</h5>
                            <p>{{ __('Não foram encontrados usuários com certificados que correspondam à busca:') }} <strong>{{ $nomeBuscado }}</strong></p>
                            <hr>
                            <p class="mb-0">{{ __('Tente pesquisar com um nome diferente ou verifique se o nome está correto.') }}</p>
                        </div>
                    @else
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <p class="mb-0">
                                    {{ __('Foram encontrados') }} <strong>{{ $totalUsuarios }}</strong> 
                                    {{ $totalUsuarios == 1 ? __('usuário') : __('usuários') }} 
                                    {{ __('com certificados correspondentes à busca:') }} <strong>{{ $nomeBuscado }}</strong>
                                </p>
                                <small class="text-muted">
                                    {{ __('Mostrando') }} {{ $usuarios->firstItem() }} {{ __('a') }} {{ $usuarios->lastItem() }} {{ __('de') }} {{ $totalUsuarios }}
                                </small>
                            </div>
                        </div>

                        @foreach($usuarios as $usuario)
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <img src="{{ asset('img/icons/user-solid-black.svg') }}" alt="Usuário" style="width: 20px; height: 20px; margin-right: 8px;">
                                        {{ $usuario->name }}
                                        @if($usuario->email)
                                            <small class="text-muted">({{ $usuario->email }})</small>
                                        @endif
                                    </h5>
                                    <small class="text-muted">
                                        {{ __('Total de certificados:') }} 
                                        <span class="badge bg-success">{{ $usuario->certificados->count() }}</span>
                                    </small>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Evento') }}</th>
                                                    <th>{{ __('Tipo') }}</th>
                                                    <th>{{ __('Data de Emissão') }}</th>
                                                    <th class="text-center">{{ __('Ações') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($usuario->certificados as $certificado)
                                                    <tr>
                                                        <td>{{ $certificado->evento->nome ?? 'N/A' }}</td>
                                                        <td>
                                                            <span class="badge bg-secondary">
                                                                {{ $tiposCertificados[$certificado->tipo] ?? 'Desconhecido' }}
                                                            </span>
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($certificado->pivot->created_at)->format('d/m/Y H:i') }}</td>
                                                        <td class="text-center">
                                                            @php
                                                                $trabalhoId = 0;
                                                                switch($certificado->tipo) {
                                                                    case 1: // apresentador
                                                                        $trabalhoId = $certificado->pivot->trabalho_id ?? 0;
                                                                        break;
                                                                    case 6: // expositor
                                                                        $trabalhoId = $certificado->pivot->palestra_id ?? 0;
                                                                        break;
                                                                    case 8: // outras_comissoes
                                                                        $trabalhoId = $certificado->pivot->comissao_id ?? 0;
                                                                        break;
                                                                    case 9: // inscrito_atividade
                                                                        $trabalhoId = $certificado->pivot->atividade_id ?? 0;
                                                                        break;
                                                                }
                                                            @endphp
                                                            <a href="{{ route('verCertificado', [$certificado->id, $usuario->id, $trabalhoId]) }}" 
                                                               target="_blank" 
                                                               class="btn btn-sm btn-outline-success"
                                                               title="{{ __('Visualizar Certificado') }}">
                                                                <img src="{{ asset('img/icons/eye-regular.svg') }}" alt="Visualizar" style="width: 16px; height: 16px; margin-right: 4px;">
                                                                {{ __('Visualizar') }}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- Paginação --}}
                        @if($usuarios->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $usuarios->onEachSide(2)->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    @endif
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <div>
                        @if(!$usuarios->isEmpty())
                            <small class="text-muted">
                                {{ __('Página') }} {{ $usuarios->currentPage() }} {{ __('de') }} {{ $usuarios->lastPage() }}
                            </small>
                        @endif
                    </div>
                    <a href="{{ route('validarCertificado') }}" class="btn btn-secondary">
                        <img src="{{ asset('img/icons/previous.svg') }}" alt="Voltar" style="width: 16px; height: 16px; margin-right: 4px;">
                        {{ __('Voltar à Validação') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- paginaçao -->
<style>
.pagination {
    margin-bottom: 0;
}

.page-link {
    color: #007bff;
}

.page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}

.page-item.disabled .page-link {
    color: #6c757d;
}
</style>

@endsection

