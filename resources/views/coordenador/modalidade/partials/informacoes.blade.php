<x-forms.section
    title="Informações gerais"
    icon="bi-file-earmark-text"
>
    <div class="row">
        <div class="form-group">
            <label for="nome" class="col-form-label font-weight-bold">{{ __('Nome') }}</label>
            <input id="nome" type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" value="{{ old('nome', $modalidade->nome ?? '') }}" required autocomplete="nome" autofocus>

            @error('nome')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group col-md-6">
            <label for="numMaxCoautores" class="col-form-label font-weight-bold">{{ __('Número de coautores') }}</label>
            <input id="numMaxCoautores" type="number" class="form-control @error('numMaxCoautores') is-invalid @enderror" name="numMaxCoautores" value="{{ old('numMaxCoautores', $modalidade->numMaxCoautores ?? '') }}" autocomplete="numMaxCoautores" autofocus>

            @error('numMaxCoautores')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        @if ($evento->is_multilingual)
            <div class="row mt-2"> {{-- Label Row (mt-2 para espaçamento opcional) --}}
                <div class="col-sm-12">
                    <label for="nome_en" class="col-form-label font-weight-bold">{{ __('Nome (Inglês)') }}</label> {{-- Sem '*' se for nullable --}}

                    <input id="nome_en" type="text" class="form-control @error('nome_en') is-invalid @enderror" name="nome_en" value="{{ old('nome_en', $modalidade->nome_en) }}" autocomplete="off"> {{-- Sem 'required' se for nullable --}}
                    @error('nome_en')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>{{-- end row --}}
            <div class="row mt-2"> {{-- Label Row (mt-2 para espaçamento opcional) --}}
                <div class="col-sm-12">
                    <label for="nome_es" class="col-form-label font-weight-bold">{{ __('Nome (Espanhol)') }}</label> {{-- Sem '*' se for nullable --}}
                    <input id="nome_es" type="text" class="form-control @error('nome_es') is-invalid @enderror" name="nome_es" value="{{ old('nome_es', $modalidade->nome_es) }}" autocomplete="off"> {{-- Sem 'required' se for nullable --}}

                    @error('nome_es')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>{{-- end row --}}
        @endif
</x-forms.section>
