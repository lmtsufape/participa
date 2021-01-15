@extends('coordenador.detalhesEvento')

@section('menu')

<div class="container">
            <h1>Pagamentos </h1>

            <table class="table">
			  <thead>
			    <tr>
			      <th scope="col">Inscrito</th>
			      <th scope="col">Status</th>
			      <th scope="col">Criado</th>
			      <th scope="col">Tipo de Pagamento</th>
			    </tr>
			  </thead>
			  <tbody>
			  	@foreach ($inscricaos as $inscricao)
				    <tr>
				      <th scope="row">{{ $inscricao->user->name }}</th>
				      <td>{{ $inscricao->pagamento->pagseguro_status }}</td>
				      <td>{{ $inscricao->pagamento->created_at }}</td>
				      <td>{{ $inscricao->pagamento->tipo_pagamento_id }}</td>
				    </tr>			  		
			  	@endforeach

			  </tbody>
			</table>
    
</div>

@endsection













