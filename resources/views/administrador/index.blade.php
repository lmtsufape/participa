@extends('layouts.app')

@section('content')

<div class="container"  style="position: relative; top: 80px;">
    	<h2 style="margin-top: 100px; ">
    		{{ Auth()->user()->name }} - Perfil: Administrador
    	</h2>
    	
       	<div class="row justify-content-center d-flex align-items-center">	      
	      @include('pages.card_index', ['nome' => 'Editais',  'rota' => route('admin.editais')])
	      @include('pages.card_index', ['nome' => "Áreas", 	  'rota' => route('admin.areas')])
	      @include('pages.card_index', ['nome' => "Usuários",  'rota' => route('admin.usuarios')])
	 	</div>
</div>

@endsection
