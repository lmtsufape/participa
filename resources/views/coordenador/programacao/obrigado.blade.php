@extends('layouts.app')


@section('content')

    <div class="container">
        <div class="row justify-content-center titulo">
            <div class="col-sm-12">
                <h2 class="alert alert-success">
					Muito obrigado!
					<br>
					<br>
					Sua inscrição foi processada com sucesso, código da inscrição: {{ request()->get('code') }}
				</h2>
				
            </div>
            
        </div>
        <div class="row justify-content-center">
            
                <a href="{{ route('home') }}" class="btn btn-primary"  >Voltar</a>
        </div>  
    </div>


@endsection