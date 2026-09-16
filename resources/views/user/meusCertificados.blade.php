@extends('layouts.app')

@use('App\Enums\TipoCertificado')

@section('content')

    <div class="container">
        <x-admin.content-header
            title="{{ __('Certificados') }}"
            description="Consulte e acesse os certificados disponíveis para sua participação nos eventos."
        />


        @foreach ($certificadosPorTipo as $tipo => $emissoes)

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title pt-2">Tipo do certificado: <span
                        class="text-muted fw-normal">{{ $emissoes->first()->certificado->tipo->label() }}</span></h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">
                                        Evento
                                    </th>
                                    @if (in_array($emissoes->first()->certificado->tipo, [
                                        TipoCertificado::Apresentador,
                                        TipoCertificado::Palestrante,
                                        TipoCertificado::OutrasComissoes,
                                    ]))
                                        <th>
                                            {{ match ($emissoes->first()->certificado->tipo) {
                                                TipoCertificado::Apresentador => 'Trabalho',
                                                TipoCertificado::Palestrante => 'Palestra',
                                                TipoCertificado::OutrasComissoes => 'Comissão',
                                            } }}
                                        </th>
                                    @endif
                                    <th scope="col">
                                        Data
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($emissoes as $emissao)
                                    <tr>
                                        <td>
                                            {{ $emissao->certificado->evento->nome }}
                                        </td>
                                        @if (in_array($emissoes->first()->certificado->tipo, [
                                            TipoCertificado::Apresentador,
                                            TipoCertificado::Palestrante,
                                            TipoCertificado::OutrasComissoes,
                                        ], true))
                                            <td>
                                                {{ match ($emissao->certificado->tipo) {
                                                    TipoCertificado::Apresentador => $emissao->trabalho?->titulo,
                                                    TipoCertificado::Palestrante => $emissao->palestra?->nome,
                                                    TipoCertificado::OutrasComissoes => $emissao->tipoComissao?->nome,
                                                } }}
                                            </td>
                                        @endif
                                        <td>
                                            {{ $emissao->created_at?->format('d/m/Y H:i') }}

                                        </td>
                                        @php
                                            $referencia_id = match ($emissao->certificado->tipo) {
                                                TipoCertificado::Apresentador => $emissao->trabalho_id,
                                                TipoCertificado::Palestrante => $emissao->palestra_id,
                                                TipoCertificado::OutrasComissoes => $emissao->comissao_id,
                                                default => 0,
                                            };
                                        @endphp

                                        <td>
                                            <a
                                                class="text-reset d-flex justify-content-center"
                                                href="{{ route('verCertificado', [
                                                    $emissao->certificado->id,
                                                    $usuario->id,
                                                    $referencia_id
                                                ]) }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                            >
                                                <i class="bi bi-file-earmark-text text-my-primary"></i>
                                            </a>

                                            @error('certificado')
                                                {{ $message }}
                                            @enderror
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        @endforeach

@endsection
