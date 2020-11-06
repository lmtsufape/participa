@extends('coordenador.detalhesEvento')

@section('menu')

    <div id="divDefinirCoordComissaoOrganizadora" class="comissao" style="display: block">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="titulo-detalhes">Definir Coordenador da Comissão Organizadora</h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Coordenador da Comissão</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Selecione um coordenador para comissão do seu evento</h6>
                        <form id="formCoordComissao" action="{{route('cadastrar.coordComissao')}}" method="POST">
                            @csrf
                            <p class="card-text">
                                    <input type="hidden" name="eventoId" value="{{ $evento->id ?? '' }}">

                                    <div class="form-group">
                                        <label for="coodComissaoId">Coordenador Comissão</label>
                                        <select class="form-control" name="coordComissaoId" id="coodComissaoId" required>
                                            <option value="">-- E-mail do coordenador --</option>
                                            @foreach ($users as $user)
                                                @if($evento->coordComissaoId == $user->id)
                                                    <option value="{{$user->id}}" selected>{{$user->email}}</option>
                                                @else
                                                    <option value="{{$user->id}}">{{$user->email}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </p>
                                <div class="row justify-content-center">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary" style="width:100%">
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
