@extends('coordenador.detalhesEvento')

@section('menu')

    <div id="divDefinirCoordComissaoOrganizadora" class="comissao" style="display: block">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Definir Coordenadores da Comissão Organizadora</h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-10 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Coordenadores da Comissão Organizadora</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Selecione os coordenadores para comissão organizadora do seu evento</h6>
                        <form id="formCoordComissao" action="{{route('comissaoOrganizadora.salvaCoordenador')}}" method="POST">
                            @csrf
                            <p class="card-text">
                                <input type="hidden" name="eventoId" value="{{ $evento->id ?? '' }}">
                                <div class="form-group">
                                    @foreach ($users as $user)
                                        <div class="form-check">
                                            <input class="form-check-input" name="coordComissaoId[]" @if(in_array($user->id, old('coordComissaoId[]', $coordenadores))) checked @endif type="checkbox" value="{{$user->id}}" id="check{{$user->id}}">
                                            <label class="form-check-label" for="check{{$user->id}}">
                                                {{$user->email}} - {{$user->name}}
                                            </label>
                                            </div>
                                    @endforeach
                                </div>
                                @error('coordComissaoId')
                                    <span class="invalid-feedback block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </p>
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <button id="submitButton" type="submit" class="btn btn-primary" style="width:100%">
                                        {{ __('Definir Coordenador') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>{{-- End Cord Comissão --}}

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[name="coordComissaoId[]"]');
            const submitBtn  = document.getElementById('submitButton');

            function toggleSubmit() {
                // habilita se houver pelo menos um checkbox marcado
                const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                submitBtn.disabled = !anyChecked;
            }

            // observe mudanças
            checkboxes.forEach(cb => {
                cb.addEventListener('change', toggleSubmit);

                // opcional: mostrar/ocultar a área temática junto
                const container = cb.closest('.card').querySelector('.area-select-container');
                cb.addEventListener('change', () => {
                    container.classList.toggle('d-none', !cb.checked);
                });
            });

            // estado inicial (caso haja old values)
            toggleSubmit();
        });
    </script>
@endpush
