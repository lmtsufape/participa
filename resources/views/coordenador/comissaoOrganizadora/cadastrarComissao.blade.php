@extends('coordenador.detalhesEvento')

@section('menu')
    {{-- Comissão --}}
    <div id="divCadastrarComissaoOrganizadora" class="comissao" style="display: block">
        @error ('cadastrarComissao')
            @include('componentes.mensagens')
        @enderror
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Cadastrar Membro da Comissão Organizadora</h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-5">
                <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Novo Membro</h5>
                      <h6 class="card-subtitle mb-2 text-muted">Cadastre um membro para sua Comissão</h6>
                      <form action="{{route('cadastrar.comissao')}}" method="POST">
                        @csrf
                        <input type="hidden" name="eventoId" value="{{ $evento->id ?? '' }}">
                        <p class="card-text">
                            <div class="row justify-content-center">
                                <div class="col-sm-12">
                                    <label for="emailMembroComissao" class="control-label">E-mail do novo membro</label>
                                    <input type="email" name="emailMembroComissao" class="form-control @error('emailMembroComissao') is-invalid @enderror" name="emailMembroComissao" value="{{ old('emailMembroComissao') }}" id="emailMembroComissao" placeholder="E-mail" required>
                                    @error('emailMembroComissao')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                        </div>
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
                  </div>{{-- end card--}}
            </div>

        </div>

    </div>{{-- End cadastrar Comissão --}}

@endsection
