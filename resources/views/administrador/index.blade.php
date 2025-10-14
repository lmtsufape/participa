@extends('layouts.app')

@section('content')

<div class="container position-relative">
    	<h2>
    		{{ Auth()->user()->name }} - {{ __('Perfil: Administrador') }}
    	</h2>

       	<div class="row justify-content-center d-flex align-items-center">
	      @include('pages.card_index', ['nome' => 'Eventos',  'rota' => route('admin.eventos')])
	      @include('pages.card_index', ['nome' => "Usuários",  'rota' => route('admin.users')])
	      @include('pages.card_index', ['nome' => "Confirmação de inscrição",  'rota' => route('admin.relatorio.form')])

	 	</div>
</div>

@endsection
