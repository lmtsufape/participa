@extends('layouts.app')

@section('content')
<div class="container-fluid content">
    <div class="row justify-content-center">
        <div class="banner-evento">
            <img src="{{asset('img/colorscheme.png')}}" alt="">
        </div>
        <img class="front-image-evento" src="{{asset('img/colorscheme.png')}}" alt="">
    </div>
</div>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-10">
            <h1>{{$evento->nome}}</h1>
        </div>
    </div>
</div>
@endsection