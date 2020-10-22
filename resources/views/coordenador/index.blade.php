@extends('layouts.app')

@section('content')

<div class="container">
    	<h2 style="margin-top: 100px; ">
    		{{ Auth()->user()->name }} - Perfil: Coord. de Eventos
    	</h2>
    	
       	<div class="row justify-content-center d-flex align-items-center">	      
	      @include('pages.card_index', ['nome' => 'Eventos',  'rota' => route('coord.eventos')])
	      @include('pages.card_index', ['nome' => "Áreas", 	  'rota' => route('cientifica.areas')])
	      @include('pages.card_index', ['nome' => "Usuários",  'rota' => route('cientifica.usuarios')])
	 	</div>
</div>

@endsection
