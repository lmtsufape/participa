@php($versaoFinalEnviada = $trabalho->versoesFinais()->exists())
@if($trabalho->modalidade->versaoFinalHabilitada() || $versaoFinalEnviada)
    @can('visualizarVersaoFinal', $trabalho)
        @if($versaoFinalEnviada)
            <span class="badge bg-success">Enviada</span>
        @endif
        <a class="btn btn-sm btn-outline-primary my-1" href="{{ route('trabalho.versao-final.index', $trabalho) }}">
            @if($versaoFinalEnviada)
                Consultar versão final
            @else
                @can('enviarVersaoFinal', $trabalho)
                    Enviar versão final
                @else
                    Versão final
                @endcan
            @endif
        </a>
    @endcan
@endif
