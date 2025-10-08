@extends('coordenador.detalhesEvento')

@section('menu')

<div class="container position-relative">
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
    @if(session('error'))
        <div class="row">
            <div class="col-md-12" style="margin-top: 5px;">
                <div class="alert alert-danger">
                    <p>{{session('error')}}</p>
                </div>
            </div>
        </div>
    @endif
    <div class="row cards-eventos-index">
        @foreach ($assinaturas as $assinatura)
            @can('isCoordenadorOrCoordenadorDasComissoes', $evento)
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
                                    </div>
                                </h5>
                            </div>
                        </div>
                        <div>
                            <div>
                                <a href="{{route('coord.editarAssinatura', ['eventoId' => $assinatura->evento->id, 'id' => $assinatura->id])}}">
                                    <img src="{{asset('img/icons/edit-regular.svg')}}" alt="Editar" style="width: 16px; height: 16px; margin-right: 8px;">Editar
                                </a>
                            </div>
                            <div>
                                <a data-bs-toggle="modal" data-bs-target="#modalStaticDeletarAssinatura_{{$assinatura->id}}" style="color: red; cursor: pointer;">
                                    <img src="{{asset('img/icons/trash-alt-regular.svg')}}" alt="Deletar assinatura" style="width: 16px; height: 16px; margin-right: 8px;">Deletar assinatura
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        @endforeach
    </div>
</div>

@endsection


<!-- Modal deletar assinatura -->
@foreach($assinaturas as $assinatura)
    <div class="modal fade" id="modalStaticDeletarAssinatura_{{$assinatura->id}}" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #114048ff; color: white;">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Deletar assinatura</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="deletar-assinatura-form-{{$assinatura->id}}" method="POST" action="{{route('coord.assinatura.destroy', $assinatura->id)}}">
                        @csrf
                        Tem certeza que deseja deletar a assinatura de {{$assinatura->nome}}?
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger" form="deletar-assinatura-form-{{$assinatura->id}}">Sim</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
