@extends('layouts.app')

@section('content')

<div class="container position-relative">

    {{-- titulo da página --}}
    <div class="row justify-content-center titulo-detalhes">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-10">
                    <h1>{{__('Meus Eventos')}}</h1>
                </div>
                <div class="col-sm-2">
                    <a href="{{route('evento.criar')}}" class="btn btn-primary">{{__('Novo Evento')}}</a>
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
    <div class="container-fluid content-row">
        <div class="row">
        @foreach ($eventos as $evento)
            @if ($evento->deletado == false)
                @can('isCoordenadorOrCoordenadorDasComissoes', $evento)
                    <div class="col-md-4 mt-2 d-flex align-items-stretch">
                        <div class="card">
                            @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en' && isset($evento->fotoEvento_en))
                                <img src="{{asset('storage/'.$evento->fotoEvento_en)}}" class="card-img-top" alt="...">
                            @elseif(isset($evento->fotoEvento))
                                <img src="{{asset('storage/'.$evento->fotoEvento)}}" class="card-img-top" alt="...">
                            @else
                                <img src="{{asset('img/colorscheme.png')}}" class="card-img-top" alt="..." style="height: 150px;">
                            @endif
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h5 class="card-title">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <a href="{{route('evento.visualizar',['id'=>$evento->id])}}" style="text-decoration: inherit;">
                                                        @if($evento->is_multilingual && Session::get('idiomaAtual') === 'en')
                                                            {{$evento->nome_en}}
                                                        @else
                                                            {{$evento->nome}}
                                                        @endif
                                                    </a>
                                                </div>
                                            </div>

                                        </h5>
                                    </div>
                                </div>
                                <div>
                                    <p class="card-text">
                                        <img src="{{ asset('/img/icons/calendar.png') }}" alt="" width="20px;" style="position: relative; top: -2px;"> {{date('d/m/Y',strtotime($evento->dataInicio))}} - {{date('d/m/Y',strtotime($evento->dataFim))}}<br>
                                        {{-- <strong>Submissão:</strong> {{date('d/m/Y',strtotime($evento->inicioSubmissao))}} - {{date('d/m/Y',strtotime($evento->fimSubmissao))}}<br>
                                        <strong>Revisão:</strong> {{date('d/m/Y',strtotime($evento->inicioRevisao))}} - {{date('d/m/Y',strtotime($evento->fimRevisao))}}<br> --}}
                                    </p>
                                    <p>
                                        <div class="row justify-content-center">
                                            <div class="col-sm-12">
                                                <img src="{{ asset('/img/icons/location_pointer.png') }}" alt="" width="18px" height="auto">
                                                {{$evento->endereco->rua}}, {{$evento->endereco->numero}} - {{$evento->endereco->cidade}} / {{$evento->endereco->uf}}.
                                            </div>
                                        </div>
                                    </p>
                                    <div class="row col-md-12">
                                        <div class="row col-md-12">
                                            <a href="{{route('evento.visualizar',['id'=>$evento->id])}}">
                                                <i class="far fa-eye" style="color: black"></i>&nbsp;&nbsp;{{__('Visualizar evento')}}
                                            </a>
                                        </div>
                                        <div class="row col-md-12">
                                            <a href="{{ route('coord.detalhesEvento', ['eventoId' => $evento->id]) }}">
                                                <i class="fas fa-cog" style="color: black"></i>&nbsp;&nbsp;{{__('Configurar evento')}}
                                            </a>
                                        </div>
                                        @can('isCriador', $evento)
                                        <div class="row col-md-12">
                                            <form id="formExcluirEvento{{$evento->id}}" method="POST" action="{{route('evento.deletar',$evento->id)}}">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <a href="#" data-toggle="modal" data-target="#modalExcluirEvento{{$evento->id}}">
                                                    <i class="far fa-trash-alt" style="color: black"></i>&nbsp;&nbsp;{{__('Deletar')}}
                                                </a>
                                            </form>
                                        </div>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
            @endif
        @endforeach
    </div>
    @foreach ($eventos as $evento)
        @if ($evento->deletado == false)
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
                            Tem certeza de deseja excluir esse evento?
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                        <button type="submit" class="btn btn-primary" form="formExcluirEvento{{$evento->id}}">Sim</button>
                    </div>
                </div>
                </div>
            </div>
            <!-- fim do modal -->
        @endif
    @endforeach

</div>

@endsection
