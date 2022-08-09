@extends('coordenador.detalhesEvento')

@section('menu')
<div id="divEditarEtiquetas" class="eventos" style="display: block">
    <div class="row">
        <div class="col-sm-12">
            <h1 class="titulo-detalhes">Módulos que seu evento pode ter</h1>
        </div>
    </div>
    {{-- Habilitar Modulos --}}
    <div class="row justify-content-center">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Módulos</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Escolha quais módulos serão exibidos</h6>
                    <form method="POST" action="{{route('exibir.modulo', $evento->id)}}">
                    @csrf

                    <p class="card-text">
                        <input type="hidden" name="modinscricao" value="false">
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="modinscricao" class="col-form-label">{{ __('Inscrições') }}</label>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-sm-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" @if($modulos->modinscricao) checked @endif name="modinscricao" id="modinscricao">
                                    <label class="form-check-label" for="modinscricao">
                                    Habilitar
                                    </label>
                                </div>

                                @error('modinscricao')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>{{-- end row--}}
                    </p>

                    <p class="card-text">
                    <input type="hidden" name="modprogramacao" value="false">
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="modprogramacao" class="col-form-label">{{ __('Programação') }}</label>
                        </div>
                    </div>
                    <div class="row justify-content-start">
                        <div class="col-sm-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" @if($modulos->modprogramacao) checked @endif name="modprogramacao" id="modprogramacao">
                                <label class="form-check-label" for="modprogramacao">
                                    Habilitar
                                </label>
                            </div>

                            @error('modprogramacao')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        {{-- <input type="hidden" name="modarquivo" value="false"> --}}
                        {{-- <div class="col-sm-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" @if($evento->modarquivo) checked @endif name="modarquivo" id="modarquivo">
                                <label class="form-check-label" for="modarquivo">
                                Exibir arquivo adicional
                                </label>
                            </div>
                        </div> --}}

                        </div>
                        <div class="row justify-content-center">
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" @if($modulos->modprogramacao == true && $evento->exibir_calendario_programacao) checked @endif name="exibir_calendario" id="exibir_calendario">
                                    <label class="form-check-label" for="exibir_calendario">
                                    Exibir com calendário
                                    </label>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" @if($modulos->modprogramacao == true && $evento->exibir_pdf) checked @endif name="exibir_pdf" id="exibir_pdf">
                                    <label class="form-check-label" for="exibir_pdf">
                                    Exibir o pdf enviado
                                    </label>
                                </div>
                            </div>
                        </div>{{-- end row--}}

                    </p>

                    <p class="card-text">
                    <input type="hidden" name="modorganizacao" value="false">
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="modorganizacao" class="col-form-label">{{ __('Organização e Apoio') }}</label>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-sm-12">

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" @if($modulos->modorganizacao) checked @endif name="modorganizacao" id="modorganizacao">
                                <label class="form-check-label" for="modorganizacao">
                                    Habilitar
                                </label>
                            </div>

                            @error('modorganizacao')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                    </div>{{-- end row--}}

                    </p>

                    <p class="card-text">
                    <input type="hidden" name="modsubmissao" value="false">
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="modsubmissao" class="col-form-label">{{ __('Submissões de Trabalhos') }}</label>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-sm-12">

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" @if($modulos->modsubmissao) checked @endif name="modsubmissao" id="modsubmissao">
                                <label class="form-check-label" for="modsubmissao">
                                    Habilitar
                                </label>
                            </div>

                            @error('modsubmissao')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                    </div>{{-- end row--}}

                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary" style="width:100%">
                                {{ __('Finalizar') }}
                            </button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Fim --}}

</div>
@endsection
