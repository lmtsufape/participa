@extends('layouts.app')

@section('content')

<div class="container"  style="position: relative; top: 80px;">
    	<h2 style="margin-top: 100px; ">
    		Usuários
    	</h2>
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
       	<div class="row justify-content-center d-flex align-items-center">
	      @include('tables.table_users', ['users' => $users])
	 	</div>
</div>

@endsection
