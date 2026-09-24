<x-forms.section
    title="Cronograma da modalidade"
    description="Defina os períodos das etapas desta modalidade."
    icon="bi-calendar-week"
>

    {{-- Submissão --}}
    <div class="pb-4 border-bottom">
        <div class="mb-3">
            <h6 class="fw-semibold mb-1">
                Submissão
            </h6>

            <p class="text-muted small mb-0">
                Período em que os participantes poderão enviar seus trabalhos.
            </p>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label for="inicioSubmissao" class="form-label fw-semibold">
                    Início
                </label>

                <input
                    id="inicioSubmissao"
                    type="datetime-local"
                    class="form-control @error('inicioSubmissao') is-invalid @enderror"
                    name="inicioSubmissao"
                    value="{{ old('inicioSubmissao', $modalidade->inicioSubmissao ?? '') }}"
                >

                @error('inicioSubmissao')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="fimSubmissao" class="form-label fw-semibold">
                    Fim
                </label>

                <input
                    id="fimSubmissao"
                    type="datetime-local"
                    class="form-control @error('fimSubmissao') is-invalid @enderror"
                    name="fimSubmissao"
                    value="{{ old('fimSubmissao', $modalidade->fimSubmissao ?? '') }}"
                >

                @error('fimSubmissao')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>


    {{-- Avaliação --}}
    <div class="py-4 border-bottom">
        <div class="mb-3">
            <h6 class="fw-semibold mb-1">
                Avaliação
            </h6>

            <p class="text-muted small mb-0">
                Período destinado à avaliação dos trabalhos submetidos.
            </p>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label for="inicioRevisao" class="form-label fw-semibold">
                    Início
                </label>

                <input
                    id="inicioRevisao"
                    type="datetime-local"
                    class="form-control @error('inicioRevisao') is-invalid @enderror"
                    name="inicioRevisao"
                    value="{{ old('inicioRevisao', $modalidade->inicioRevisao ?? '') }}"
                >

                @error('inicioRevisao')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="fimRevisao" class="form-label fw-semibold">
                    Fim
                </label>

                <input
                    id="fimRevisao"
                    type="datetime-local"
                    class="form-control @error('fimRevisao') is-invalid @enderror"
                    name="fimRevisao"
                    value="{{ old('fimRevisao', $modalidade->fimRevisao ?? '') }}"
                >

                @error('fimRevisao')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>


    {{-- Correção --}}
    <div class="py-4 border-bottom">
        <div class="mb-3">
            <h6 class="fw-semibold mb-1">
                Correção
            </h6>

            <p class="text-muted small mb-0">
                Período para envio das correções solicitadas.
            </p>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label for="inicioCorrecao" class="form-label fw-semibold">
                    Início
                </label>

                <input
                    id="inicioCorrecao"
                    type="datetime-local"
                    class="form-control @error('inicioCorrecao') is-invalid @enderror"
                    name="inicioCorrecao"
                    value="{{ old('inicioCorrecao', $modalidade->inicioCorrecao ?? '') }}"
                >

                @error('inicioCorrecao')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="fimCorrecao" class="form-label fw-semibold">
                    Fim
                </label>

                <input
                    id="fimCorrecao"
                    type="datetime-local"
                    class="form-control @error('fimCorrecao') is-invalid @enderror"
                    name="fimCorrecao"
                    value="{{ old('fimCorrecao', $modalidade->fimCorrecao ?? '') }}"
                >

                @error('fimCorrecao')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>


    {{-- Validação --}}
    <div class="py-4 border-bottom">
        <div class="mb-3">
            <h6 class="fw-semibold mb-1">
                Validação
            </h6>

            <p class="text-muted small mb-0">
                Período destinado à validação das correções realizadas.
            </p>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label for="inicioValidacao" class="form-label fw-semibold">
                    Início
                </label>

                <input
                    id="inicioValidacao"
                    type="datetime-local"
                    class="form-control @error('inicioValidacao') is-invalid @enderror"
                    name="inicioValidacao"
                    value="{{ old('inicioValidacao', $modalidade->inicioValidacao ?? '') }}"
                >

                @error('inicioValidacao')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="fimValidacao" class="form-label fw-semibold">
                    Fim
                </label>

                <input
                    id="fimValidacao"
                    type="datetime-local"
                    class="form-control @error('fimValidacao') is-invalid @enderror"
                    name="fimValidacao"
                    value="{{ old('fimValidacao', $modalidade->fimValidacao ?? '') }}"
                >

                @error('fimValidacao')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>


    {{-- Resultado --}}
    <div class="pt-4">
        <div class="mb-3">
            <h6 class="fw-semibold mb-1">
                Resultado
            </h6>

            <p class="text-muted small mb-0">
                Defina quando o resultado da modalidade será divulgado.
            </p>
        </div>

        <div class="row">
            <div class="col-md-6">
                <label for="inicioResultado" class="form-label fw-semibold">
                    Data e hora da divulgação
                </label>

                <input
                    id="inicioResultado"
                    type="datetime-local"
                    class="form-control @error('inicioResultado') is-invalid @enderror"
                    name="inicioResultado"
                    value="{{ old('inicioResultado', $modalidade->inicioResultado ?? '') }}"
                >

                @error('inicioResultado')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>

</x-forms.section>
