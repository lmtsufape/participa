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
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In faucibus enim non erat fermentum rhoncus. Suspendisse tempor tristique commodo. Fusce sed gravida felis. Aliquam est ante, ullamcorper non semper quis, mattis id nisl. Nam et justo nec urna lacinia suscipit ac vel diam. Donec semper lectus urna, non ornare eros faucibus vitae. Aenean eu porttitor dui. Vestibulum et porta lectus. In a lobortis lacus, et fringilla mi. Sed tincidunt sapien ex. Ut cursus massa pretium enim egestas lobortis. Duis molestie sem a dolor sagittis dictum. Etiam facilisis auctor lobortis.</p>
        </div>
    </div>
    <div class="row margin">
        <div class="col-sm-12">
            <h4>Realização do Evento</h4>
            {{date('d/m/Y',strtotime($evento->dataInicio))}} - {{date('d/m/Y',strtotime($evento->dataFim))}}
        </div>
    </div>
    <div class="row margin">
        <div class="col-sm-12">
            <h4>Submissão de Trabalhos</h4>
            {{date('d/m/Y',strtotime($evento->inicioSubmissao))}} - {{date('d/m/Y',strtotime($evento->fimSubmissao))}}
        </div>
    </div>
    <div class="row margin">
        <div class="col-sm-12">
            <h4>Revisão de Trabalhos</h4>
            {{date('d/m/Y',strtotime($evento->inicioRevisao))}} - {{date('d/m/Y',strtotime($evento->fimRevisao))}}
        </div>
    </div>

    <div class="row margin">
        <div class="col-sm-12">
            <h4>Endereço</h4>
            {{$evento->endereco->rua}}, {{$evento->endereco->numero}} - {{$evento->endereco->cidade}} / {{$evento->endereco->uf}}.
        </div>
    </div>
    
    <div class="row justify-content-center" style="margin: 20px 0 20px 0">

        <div class="col-md-6" style="padding-left:0">
            <a class="btn btn-secondary botao-form" href="#" style="width:100%">Submeter Trabalho</a>
        </div>
        <div class="col-md-6" style="padding-right:0">
            <a class="btn btn-primary botao-form" href="#" style="width:100%">Fazer Inscrição</a>
        </div>
    </div>
</div>


@endsection

@section('javascript')
<script>
    
</script>
@endsection