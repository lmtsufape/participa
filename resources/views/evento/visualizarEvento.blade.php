@extends('layouts.app')

@section('content')
<div class="container-fluid content">
    <div class="row">
        <div class="banner-evento">
            <img src="{{asset('img/colorscheme.png')}}" alt="">
        </div>
        <img class="front-image-evento" src="{{asset('img/colorscheme.png')}}" alt="">

    </div>
</div>
<div class="container" style="margin-top:20px">
    <div class="row margin">
        <div class="col-sm-12">
            <h1>
                {{$evento->nome}}
            </h1>
        </div>
    </div>

    <div class="row margin">
        <div class="col-sm-12">
            <h4>Descrição</h4>
        </div>
    </div>
    <div class="row margin">
        <div class="col-sm-12">
            <p>{{$evento->descricao}}</p>
        </div>
    </div>
    <div class="row margin">
        <div class="col-sm-12 info-evento">
            <h4>Realização do Evento</h4>
            <p>
                <img class="" src="{{asset('img/icons/calendar-evento.svg')}}" alt="">
                {{date('d/m/Y',strtotime($evento->dataInicio))}} - {{date('d/m/Y',strtotime($evento->dataFim))}}
            </p>
        </div>
    </div>
    <div class="row margin">
        <div class="col-sm-12 info-evento">
            <h4>Submissão de Trabalhos</h4>
            <p>
                <img class="" src="{{asset('img/icons/calendar-evento.svg')}}" alt="">
                {{date('d/m/Y',strtotime($evento->inicioSubmissao))}} - {{date('d/m/Y',strtotime($evento->fimSubmissao))}}
            </p>
        </div>
    </div>
    <div class="row margin">
        <div class="col-sm-12 info-evento">
            <h4>Revisão de Trabalhos</h4>
            <p>
                <img class="" src="{{asset('img/icons/calendar-evento.svg')}}" alt="">
                {{date('d/m/Y',strtotime($evento->inicioRevisao))}} - {{date('d/m/Y',strtotime($evento->fimRevisao))}}
            </p>
        </div>
    </div>

    <div class="row margin">
        <div class="col-sm-12 info-evento">
            <h4>Endereço</h4>
            <p>
                <img class="" src="{{asset('img/icons/map-marker-alt-solid.svg')}}" alt="">
                {{$evento->endereco->rua}}, {{$evento->endereco->numero}} - {{$evento->endereco->cidade}} / {{$evento->endereco->uf}}.
            </p>
        </div>
    </div>
    @if($hasFile == true)
      <div class="row margin">
          <div class="col-sm-12 info-evento">
              <h4>Meu Trabalho</h4>
              <p>
                  <img class="" src="{{asset('img/icons/file-download-solid.svg')}}" alt="">
                  <a href="#" style="font-size: 20px; color: #114048ff;" >
                    @if($hasFile == true)
                      Baixar Trabalho
                    @else
                      Nova Versão
                    @endif
                  </a>
              </p>
          </div>
      </div>
    @endif

    <div class="row justify-content-center" style="margin: 20px 0 20px 0">

        <div class="col-md-6 botao-form-left" style="">
            <a class="btn btn-secondary botao-form" href="{{route('coord.home')}}" style="width:100%">Voltar</a>
        </div>
        <div class="col-md-6 botao-form-right" style="">
            <a class="btn btn-primary botao-form" href="{{route('trabalho.index',['id'=>$evento->id])}}" style="width:100%">Submeter Trabalho</a>
        </div>
    </div>
</div>


@endsection

@section('javascript')
<script>

</script>
@endsection
