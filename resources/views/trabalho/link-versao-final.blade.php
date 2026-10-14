@php($versaoFinalEnviada = $trabalho->versoesFinais()->exists())
@if($trabalho->modalidade->versaoFinalHabilitada() || $versaoFinalEnviada)
    @can('visualizarVersaoFinal', $trabalho)
        @if($versaoFinalEnviada)
            <span class="badge bg-success">Enviado</span>
        @endif
        <a class="btn btn-sm btn-outline-primary my-1" href="{{ route('trabalho.versao-final.index', $trabalho) }}">
            @if($versaoFinalEnviada)
                Consultar trabalho completo
            @else
                @can('enviarVersaoFinal', $trabalho)
                    Enviar trabalho completo (opcional)
                @else
                    Trabalho completo
                @endcan
            @endif
        </a>
    @endcan
@endif
