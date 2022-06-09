@extends('coordenador.detalhesEvento')

@section('menu')
<div id="divListarResultados" style="display: block">

    <div class="row titulo-detalhes justify-content-between">
        <div class="col-sm-12">
            <h1 class="">Mensangens de parecer</h1>
        </div>
    </div>
    <div class="row justify-content-center" style="width: 100%;">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row table-trabalhos">
                    <div class="col-sm-12">
                        <form action="{{route('coord.mensagem.parecer.store', $evento)}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                </div>
                            </div>
                            <div x-data="{tipo: '{{$tipo}}' }">
                                <div class="form-group">
                                    <label for="tipo" x-text="tipo"></label>
                                    <select class="form-control" id="tipo" name="tipo" x-model="tipo">
                                      <option value="evento">Evento</option>
                                      <option value="modalidade">Modalidades</option>
                                      <option value="area">Áreas</option>
                                    </select>
                                </div>
                                <div>
                                    <p>Tags que podem ser utilizadas para recuperar informação</p>
                                    <p>%NOME_EVENTO%</p>
                                    <p>%NOME_AUTOR%</p>
                                    <p>%NOME_MODALIDADE%</p>
                                    <p>%NOME_AREA%</p>
                                    <p>%TITULO_TRABALHO%</p>
                                </div>
                                <div id="evento" x-show="tipo == 'evento'">
                                    <div class="form-group">
                                        <label for="msgpositivo">Mensagem de parecer positivo</label>
                                        <textarea name="msgpositivo" class="form-control" id="msgpositivo" rows="3">@if(old('msgpositivo')) {{old('msgpositivo')}} @else {{$msgpositivo}} @endif</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="msgnegativo">Mensagem de parecer negativo</label>
                                        <textarea name="msgnegativo" class="form-control" id="msgnegativo" rows="3">@if(old('msgnegativo')) {{old('msgnegativo')}} @else {{$msgnegativo}} @endif</textarea>
                                    </div>
                                </div>
                                <div class="" id="modalidade" x-show="tipo == 'modalidade'">
                                    @foreach ($evento->modalidades as $modalidade)
                                        <div class="form-group">
                                            <label for="msgmodpositivo[{{$modalidade->id}}]">
                                                Mensagem de parecer positivo da modalidade {{$modalidade->nome}}</label>
                                            <textarea name="msgmodpositivo[{{$modalidade->id}}]" class="form-control" id="msgmodpositivo[{{$modalidade->id}}]" rows="3">@if(old('msgmodpositivo') && array_key_exists($modalidade->id, old('msgmodpositivo')) && old('msgmodpositivo')[$modalidade->id]) {{old('msgmodpositivo')[$modalidade->id]}} @else {{$msgmodpositivo[$modalidade->id]}} @endif</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="msgmodnegativo[{{$modalidade->id}}]">Mensagem de parecer negativo da modalidade {{$modalidade->nome}}</label>
                                            <textarea name="msgmodnegativo[{{$modalidade->id}}]" class="form-control" id="msgmodnegativo[{{$modalidade->id}}]" rows="3">@if(old('msgmodnegativo') && array_key_exists($modalidade->id, old('msgmodnegativo')) && old('msgmodnegativo')[$modalidade->id]) {{old('msgmodnegativo')[$modalidade->id]}} @else {{$msgmodnegativo[$modalidade->id]}} @endif</textarea>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="" id="area" x-show="tipo == 'area'">
                                    @foreach ($evento->areas as $area)
                                        <div class="form-group">
                                            <label for="msgareapositivo[{{$area->id}}]">Mensagem de parecer positivo da área {{$area->nome}}</label>
                                            <textarea name="msgareapositivo[{{$area->id}}]" class="form-control" id="msgareapositivo[{{$area->id}}]" rows="3">@if(old('msgareapositivo') && array_key_exists($area->id, old('msgareapositivo')) && old('msgareapositivo')[$area->id]) {{old('msgareapositivo')[$area->id]}} @else {{$msgareapositivo[$area->id]}} @endif</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="msgareanegativo[{{$area->id}}]">Mensagem de parecer negativo da área {{$area->nome}}</label>
                                            <textarea name="msgareanegativo[{{$area->id}}]" class="form-control" id="msgareanegativo[{{$area->id}}]" rows="3">@if(old('msgareanegativo') && array_key_exists($area->id, old('msgareanegativo')) && old('msgareanegativo')[$area->id]) {{old('msgareanegativo')[$area->id]}} @else {{$msgareanegativo[$area->id]}} @endif</textarea>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-4 col-sm-9">
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
    </div>
</div>
@endsection
