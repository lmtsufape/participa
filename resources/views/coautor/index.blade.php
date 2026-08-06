@extends('layouts.app')

@section('content')

<div class="container position-relative">

    <h2>{{ Auth()->user()->name }} - {{ __('Perfil: Coautor') }}</h2>

       <div class="row justify-content-center d-flex align-items-center">
	      <div class="col-sm-3 d-flex justify-content-center ">
	         <a href="{{route('coautor.listarTrabalhos')}}" style="text-decoration:none; color: inherit;">
	            <div class="card text-center " style="border-radius: 30px; width: 13rem;height: 15rem;">
	                  <div class="card-body d-flex justify-content-center">
	                      <h2 style="padding-top:15px">{{ __('Trabalhos') }}</h2>
	                  </div>

	            </div>
	         </a>
	      </div>

	      {{-- <div class="col-sm-3 d-flex justify-content-center">
	         <a href="#" style="text-decoration:none; color: inherit;">
	            <div class="card text-center " style="border-radius: 30px; width: 13rem;height: 15rem;">
	             <div class="card-body d-flex justify-content-center">
	                  <h2 style="padding-top:15px">Usuários</h2>
	               </div>
	            </div>
	         </a>
	      </div> --}}
	   </div>

</div>

@endsection
