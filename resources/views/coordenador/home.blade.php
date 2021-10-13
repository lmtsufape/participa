@extends('layouts.app')

@section('content')

<div class="container"  style="position: relative; top: 65px;">

    {{-- titulo da página --}}
    <div class="row justify-content-center titulo">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-10">
                    <h1>Eventos Atuais</h1>
                </div>
                <div class="col-sm-2">
                    <a href="{{route('evento.criar')}}" class="btn btn-primary">Novo Evento</a>
                </div>
            </div>
        </div>

    </div>
    @if(session('message'))
        <div class="row">
            <div class="col-md-12" style="margin-top: 5px;">
                <div class="alert alert-success">
                    <p>{{session('message')}}</p>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        {{-- {{dd($eventos)}} --}}
        @foreach ($eventos as $evento)
            @if ($evento->deletado == false)
                @can('isPublishOrIsCoordenador', $evento)
                    <div class="card" style="width: 18rem;">
                        @if(isset($evento->fotoEvento))
                        <img src="{{asset('storage/eventos/'.$evento->id.'/logo.png')}}" class="card-img-top" alt="...">
                        @else
                        <img src="{{asset('img/colorscheme.png')}}" class="card-img-top" alt="...">
                        @endif
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4 class="card-title">
                                        <div class="row justify-content-center">
                                            <div class="col-sm-12">
                                                <a href="{{route('evento.visualizar',['id'=>$evento->id])}}" style="text-decoration: inherit;">
                                                    {{$evento->nome}}
                                                </a>
                                            </div>

                                        </div>

                                    </h4>
                                </div>
                            </div>
                            <p class="card-text">
                                <strong>Realização:</strong> {{date('d/m/Y',strtotime($evento->dataInicio))}} - {{date('d/m/Y',strtotime($evento->dataFim))}}<br>
                                {{-- <strong>Submissão:</strong> {{date('d/m/Y',strtotime($evento->inicioSubmissao))}} - {{date('d/m/Y',strtotime($evento->fimSubmissao))}}<br>
                                <strong>Revisão:</strong> {{date('d/m/Y',strtotime($evento->inicioRevisao))}} - {{date('d/m/Y',strtotime($evento->fimRevisao))}}<br> --}}
                            </p>
                            <p>

                                <div class="row justify-content-center">
                                    <div class="col-sm-12">
                                        <img src="{{asset('img/icons/map-marker-alt-solid.svg')}}" alt="" style="width:15px">
                                        {{$evento->endereco->rua}}, {{$evento->endereco->numero}} - {{$evento->endereco->cidade}} / {{$evento->endereco->uf}}.
                                    </div>
                                </div>
                            </p>
                            <div>
                                <div>
                                    <a href="{{route('evento.visualizar',['id'=>$evento->id])}}">
                                        <i class="far fa-eye" style="color: black"></i>&nbsp;&nbsp;Visualizar evento
                                    </a>
                                </div>
                                @can('isCoordenador', $evento)
                                <div>
                                    <a href="{{ route('coord.detalhesEvento', ['eventoId' => $evento->id]) }}">
                                        <i class="fas fa-cog" style="color: black"></i>&nbsp;&nbsp;Configurar evento
                                    </a>
                                </div>
                                <div>
                                    <form id="formExcluirEvento{{$evento->id}}" method="POST" action="{{route('evento.deletar',$evento->id)}}">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <a href="#" data-toggle="modal" data-target="#modalExcluirEvento{{$evento->id}}">
                                            <i class="far fa-trash-alt" style="color: black"></i>&nbsp;&nbsp;Deletar
                                        </a>
                                    </form>
                                </div>
                                @endcan
                            </div>
                        </div>

                    </div>
                    <!-- Modal de exclusão do evento -->
                    <div class="modal fade" id="modalExcluirEvento{{$evento->id}}" tabindex="-1" role="dialog" aria-labelledby="#label" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #114048ff; color: white;">
                            <h5 class="modal-title" id="#label">Confirmação</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                                <div class="modal-body">
                                    Tem certeza que deseja excluir esse evento?
                                </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                                <button type="submit" class="btn btn-primary" form="formExcluirEvento{{$evento->id}}">Sim</button>
                            </div>
                        </div>
                        </div>
                    </div>
                    <!-- fim do modal -->
                @endcan
            @endif
        @endforeach
    </div>

</div>

@endsection
