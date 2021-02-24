@extends('coordenador.detalhesEvento')

@section('menu')

<div class="container">
	<div class="row titulo-detalhes">
		<h1>Pagamentos</h1>
	</div>

	<div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pagamentos</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Pagamentos realizados.</h6>
					
					<div class="container">
						<div class="row">
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
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection













