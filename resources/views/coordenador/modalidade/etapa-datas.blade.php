@php
    $editando = isset($modalidade);
    $idEtapa = $etapa . '-' . ($editando ? $modalidade->id : 'nova');
    [$inicioColuna, $fimColuna, $inicioEdit, $fimEdit, $rotulo, $rotuloCheckbox] = [
        'avaliacao' => ['inicioRevisao', 'fimRevisao', 'inícioRevisão', 'fimRevisão', 'Avaliação', 'Habilitar avaliação'],
        'correcao' => ['inicioCorrecao', 'fimCorrecao', 'inícioCorreção', 'fimCorreção', 'Correção', 'Habilitar correção'],
        'validacao' => ['inicioValidacao', 'fimValidacao', 'inícioValidação', 'fimValidação', 'Validação', 'Habilitar validação da correção'],
    ][$etapa];
    $inicioNome = $editando ? $inicioEdit . $modalidade->id : $inicioColuna;
    $fimNome = $editando ? $fimEdit . $modalidade->id : $fimColuna;
    $existente = $editando && ($modalidade->$inicioColuna || $modalidade->$fimColuna);
    $restaurar = !$editando || (string) old('modalidadeEditId') === (string) $modalidade->id;
    $habilitada = $restaurar ? (bool) old('habilitar_' . $etapa, $existente) : $existente;
    $inicioValor = $editando && $modalidade->$inicioColuna ? date('Y-m-d\TH:i', strtotime($modalidade->$inicioColuna)) : '';
    $fimValor = $editando && $modalidade->$fimColuna ? date('Y-m-d\TH:i', strtotime($modalidade->$fimColuna)) : '';
@endphp
<div class="my-3" data-etapa-modalidade data-etapa="{{ $etapa }}"
     data-existente="{{ $existente ? '1' : '0' }}" data-reabrir="{{ $editando && $restaurar && $errors->any() ? '1' : '0' }}"
     @if($editando) data-impacto-url="{{ route('modalidade.impacto-desativacao', $modalidade) }}" @endif>
    <input type="hidden" name="habilitar_{{ $etapa }}" value="0">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="habilitar-{{ $idEtapa }}"
               name="habilitar_{{ $etapa }}" value="1" data-etapa-checkbox
               aria-controls="datas-{{ $idEtapa }}" @checked($habilitada)>
        <label class="form-check-label fw-bold" for="habilitar-{{ $idEtapa }}">
            {{ $rotuloCheckbox }}
        </label>
    </div>
    @if($restaurar)
        @error('habilitar_' . $etapa)<div class="text-danger" role="alert">{{ $message }}</div>@enderror
    @endif
    <div id="datas-{{ $idEtapa }}" class="row" data-etapa-datas @if(!$habilitada) hidden @endif>
        @foreach([[$inicioNome, $inicioValor, 'Início'], [$fimNome, $fimValor, 'Fim']] as [$nomeData, $valorData, $rotuloData])
            <div class="col-sm-6">
                <label class="col-form-label" for="{{ $idEtapa }}-{{ $loop->index }}">{{ $rotuloData }} da {{ $rotulo }}</label>
                <input type="datetime-local" class="form-control" id="{{ $idEtapa }}-{{ $loop->index }}"
                       name="{{ $nomeData }}" value="{{ $restaurar ? old($nomeData, $valorData) : $valorData }}" data-original="{{ $valorData }}"
                       @if($habilitada) required @else disabled @endif>
                @if($restaurar)
                    @error($nomeData)<div class="text-danger" role="alert">{{ $message }}</div>@enderror
                @endif
            </div>
        @endforeach
    </div>
    <input type="hidden" name="confirmar_{{ $etapa }}" value="" data-etapa-confirmacao>
    <div class="alert alert-warning mt-2" data-etapa-impacto hidden role="region" aria-label="Confirmação de desativação">
        <p data-etapa-mensagem aria-live="polite"></p>
        <div class="table-responsive" style="max-height: 320px; overflow: auto" data-etapa-lista></div>
        <p>Ao salvar, as datas desta etapa serão removidas. Os resultados já registrados serão preservados.</p>
        <button type="button" class="btn btn-warning" data-etapa-confirmar hidden>Confirmar desativação</button>
        <button type="button" class="btn btn-secondary" data-etapa-cancelar>Manter etapa habilitada</button>
    </div>
</div>
@once
    <style>[data-etapa-modalidade] [hidden] { display: none !important; }</style>
    @push('scripts')
        <script src="{{ asset('js/modalidade-etapas.js') }}" defer></script>
    @endpush
@endonce
