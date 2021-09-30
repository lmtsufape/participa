@extends('coordenador.detalhesEvento')

@section('menu')

<div class="container"  style="position: relative; top: 80px;">
    <div class="row justify-content-center titulo-detalhes">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-10">
                    <h1>Assinaturas</h1>
                </div>
                <div class="col-sm-2">
                    <a href="{{ route('coord.cadastrarAssinatura', ['eventoId' => $evento->id]) }}" class="btn btn-primary">Nova Assinatura</a>
                </div>
            </div>
        </div>
    </div>
    @if(session('success'))
        <div class="row">
            <div class="col-md-12" style="margin-top: 5px;">
                <div class="alert alert-success">
                    <p>{{session('success')}}</p>
                </div>
            </div>
        </div>
    @endif
    <div class="row cards-eventos-index">
        @foreach ($assinaturas as $assinatura)
            @can('isCoordenador', $evento)
                <div class="card" style="width: 16rem;">
                    <img class="img-card" src="{{asset('storage/'.$assinatura->caminho)}}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5 class="card-title">
                                    <div class="row">
                                        <div class="col-sm-10">
                                            {{$assinatura->nome}}
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="btn-group dropright dropdown-options">
                                                <a id="options" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <a  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#"><i class="fas fa-cog "></i></a>
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a href="" class="dropdown-item">
                                                        <img src="{{asset('img/icons/eye-regular.svg')}}" class="icon-card" alt="">
                                                        Editar
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        @endforeach
    </div>
</div>

@endsection
