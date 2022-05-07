@extends('coordenador.detalhesEvento')

@section('menu')

<div class="container"  style="position: relative;">
    <div class="row justify-content-center titulo-detalhes">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-10">
                    <h1>Certificados</h1>
                </div>
                <div class="col-sm-2">
                    <a href="{{ route('coord.cadastrarCertificado', ['eventoId' => $evento->id]) }}" class="btn btn-primary">Novo Certificado</a>
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
        @foreach ($certificados as $certificado)
            @can('isCoordenadorOrCoordenadorDasComissoes', $evento)
                <div class="card" style="width: 16rem;">
                    <img class="img-card" src="{{asset('storage/'.$certificado->caminho)}}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5 class="card-title">
                                    <div class="row">
                                        <div class="col-sm-10">
                                            {{$certificado->nome}}
                                        </div>
                                    </div>
                                </h5>
                            </div>
                        </div>
                        <div>
                            <div>
                                <a href="{{route('coord.modeloCertificado', $certificado->id)}}" target="_blank">
                                    <i class="far fa-eye" style="color: black"></i>&nbsp;&nbsp;Atualizar modelo
                                </a>
                            </div>
                            <div>
                                <a href="{{route('coord.editarCertificado', ['eventoId' => $certificado->evento->id, 'id' => $certificado->id])}}">
                                    <i class="fas fa-cog" style="color: black"></i>&nbsp;&nbsp;Editar
                                </a>
                            </div>
                            <div>
                                <a href="{{route('coord.listarEmissoes', $certificado)}}">
                                    <i class="far fa-eye" style="color: black"></i>&nbsp;&nbsp;Listar certificados emitidos
                                </a>
                            </div>
                            <div>
                                <a data-toggle="modal" data-target="#modalStaticDeletarCertificado_{{$certificado->id}}" style="color: red; cursor: pointer;">
                                    <i class="far fa-trash-alt" style="color: black"></i>&nbsp;&nbsp;Deletar certificado
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

<!-- Modal deletar certificado -->
@foreach($certificados as $certificado)
    <div class="modal fade" id="modalStaticDeletarCertificado_{{$certificado->id}}" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #114048ff; color: white;">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Deletar certificado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="deletar-certificado-form-{{$certificado->id}}" method="POST" action="{{route('coord.certificado.destroy', $certificado->id)}}">
                        @csrf
                        Tem certeza que deseja deletar o certificado de {{$certificado->nome}}?
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger" form="deletar-certificado-form-{{$certificado->id}}">Sim</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

