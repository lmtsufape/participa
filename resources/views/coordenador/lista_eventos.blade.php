@extends('layouts.app')

@section('content')

<div class="container">
		<h2 style="margin-top: 100px; ">
	    	Lista de Meus Eventos
    	</h2>
    	<table class="table table-striped">
		  <thead>
		    <tr>
		      <th scope="col">#</th>
		      <th scope="col">First</th>
		      <th scope="col">Last</th>
		      <th scope="col">Handle</th>
		    </tr>
		  </thead>
		  <tbody>
		    <tr>
		      <th scope="row">1</th>
		      <td>Mark</td>
		      <td>Otto</td>
		      <td>@mdo</td>
		    </tr>		    
		  </tbody>
		</table>
		</div>

@endsection
