@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Versão final</h2>
    <p class="mb-1"><strong>{{ $trabalho->titulo }}</strong> — Trabalho #{{ $trabalho->id }}</p>
    <p>{{ $trabalho->evento->nome }}</p>

    <div class="card mb-4">
        <div class="card-header">Envio da versão final</div>
        <div class="card-body">
            @if($trabalho->modalidade->versaoFinalHabilitada())
                <p>Período de envio: de <strong>{{ $trabalho->modalidade->inicio_versao_final->format('d/m/Y H:i') }}</strong> até <strong>{{ $trabalho->modalidade->fim_versao_final->format('d/m/Y H:i') }}</strong></p>
            @else
                <p>O envio de versão final está desabilitado para esta modalidade.</p>
            @endif
            @can('enviarVersaoFinal', $trabalho)
                <p>É permitido apenas um envio de versão final por trabalho. Confira o arquivo antes de enviar: após o envio, será possível apenas consultar e baixar o arquivo. O arquivo original e as correções serão preservados.</p>
                <form action="{{ route('trabalho.versao-final.store', $trabalho) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="arquivo-versao-final" class="form-label">Arquivo da versão final</label>
                    <input type="file" name="arquivo_versao_final" id="arquivo-versao-final" class="form-control" accept=".pdf,.docx,.odt,.rtf" required aria-describedby="formatos-versao-final">
                    <small id="formatos-versao-final" class="form-text text-muted">PDF, DOCX, ODT ou RTF. Tamanho máximo: 5 MB.</small>
                    <div class="mt-3"><button type="submit" class="btn btn-primary">Enviar versão final</button></div>
                </form>
            @else
                @if($versoes->total() > 0)
                    <p class="text-muted mb-0">A versão final já foi enviada. O arquivo está disponível abaixo para consulta e download. Não é possível realizar outro envio.</p>
                @elseif($trabalho->modalidade->versaoFinalHabilitada() && now()->lt($trabalho->modalidade->inicio_versao_final))
                    <p class="text-muted mb-0">O período de envio ainda não começou.</p>
                @elseif($trabalho->modalidade->versaoFinalHabilitada() && !$trabalho->modalidade->estaEmPeriodoDeVersaoFinal())
                    <p class="text-muted mb-0">O prazo de envio foi encerrado. Os arquivos enviados continuam disponíveis para consulta.</p>
                @elseif($trabalho->modalidade->versaoFinalHabilitada())
                    <p class="text-muted mb-0">O envio está disponível somente para o autor principal de trabalhos não arquivados.</p>
                @endif
            @endcan
        </div>
    </div>

    <h4>Arquivos enviados</h4>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead><tr><th>Enviado em</th><th>Arquivo</th><th>Enviado por</th><th>Baixar</th></tr></thead>
            <tbody>
                @forelse($versoes as $versao)
                    <tr>
                        <td>{{ $versao->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $versao->nome_original }}</td>
                        <td>{{ $versao->autorEnvio?->name ?? 'Autor não disponível' }}</td>
                        <td><a class="btn btn-sm btn-outline-primary" href="{{ route('trabalho.versao-final.download', [$trabalho, $versao]) }}">Baixar arquivo</a></td>
                    </tr>
                @empty
                    <tr><td colspan="4">Nenhuma versão final enviada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $versoes->links() }}
</div>
@endsection
