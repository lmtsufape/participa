@extends('layouts.app')
@section('sidebar')


@endsection
@section('content')

        <div class="row mb-3">
            <div class="col-sm-12">
                <h1 class="text-my-primary text-center">Definir Coordenadores de Eixo Temático</h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-10 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Coordenadores de Eixo Temático</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Selecione os coordenadores para comissão científica do seu
                            evento</h6>
                        <form id="formCoordComissao" action="{{ route('cadastrar.coordEixo') }}" method="POST">
                            @csrf

                            <input type="hidden" name="evento_id" value="{{ $evento->id }}">
                            @foreach ($users as $user)
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <div class="form-check">
                                            <input class="form-check-input coord-checkbox" type="checkbox"
                                                name="coordenadores[{{ $user->id }}][ativo]"
                                                id="coord_{{ $user->id }}" value="1"
                                                {{ old("coordenadores.$user->id.ativo", $user->coordEixosTematicos()->exists()) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="coord_{{ $user->id }}">
                                                <strong>{{ $user->name }}</strong> - {{ $user->email }}
                                            </label>
                                        </div>
                                    </div>

                                    <div class="card-body area-select-container {{ old("coordenadores.$user->id.ativo") ? '' : 'd-none' }}">
                                        <label for="areas_{{ $user->id }} form-label">Áreas Temáticas:</label>
                                        <select name="coordenadores[{{ $user->id }}][areas][]"
                                            id="areas_{{ $user->id }}" class="select-areas form-control" multiple>
                                            @foreach ($areas as $area)
                                                <option value="{{ $area->id }}"
                                                    @if (in_array($area->id, old("coordenadores.$user->id.areas", $user->areas ?? []))) selected @endif>
                                                    {{ $area->nome }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endforeach
                            <div class="text-center">
                                <button id="submitButton" type="submit" class="btn btn-my-primary w-75" disabled>
                                    {{ __('Definir Coordenadores de Eixos') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.coord-checkbox');
            const submitBtn  = document.getElementById('submitButton');

            function toggleSubmit() {
                const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                submitBtn.disabled = !anyChecked;
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', toggleSubmit);
                const container = cb.closest('.card').querySelector('.area-select-container');
                cb.addEventListener('change', () => {
                    container.classList.toggle('d-none', !cb.checked);
                });
            });
            toggleSubmit();
        });
        $(document).ready(function() {
            $('.coord-checkbox').on('change', function() {
                const container = $(this).closest('.card').find('.area-select-container');

                if ($(this).is(':checked')) {
                    container.removeClass('d-none');
                } else {
                    container.addClass('d-none');
                    // Limpa a seleção dos eixos quando desmarca
                    container.find('select option').prop('selected', false);
                }
            });
            $('.select-areas').select2({
                width: '100%',
                placeholder: "Selecione as Áreas Temáticas",
                allowClear: true
            });


            $('.coord-checkbox:checked').trigger('change');
        });

    </script>
@endpush
