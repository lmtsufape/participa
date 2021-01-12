<?php

namespace App\Http\Controllers\Inscricao;

use App\Http\Controllers\Controller;
use App\Payment\PagSeguro\CreditCard;
use App\Payment\PagSeguro\Notification;
use Illuminate\Http\Request;
use App\Models\Submissao\Evento;
use App\Models\Inscricao\Promocao;
use App\Models\Inscricao\Pagamento;
use App\Models\Submissao\Atividade;
use App\Models\Inscricao\CupomDeDesconto;
use App\Models\Inscricao\CategoriaParticipante;
use App\Models\Inscricao\CampoFormulario;
use Ramsey\Uuid\Uuid;
use PagSeguro; 

// use Ramsey\Uuid\Uuid;

class CheckoutController extends Controller
{

    public function index(Request $request, $id) {
    	if(!auth()->check()) {
		    return redirect()->route('login');
	    }	    
    	
        $data = $request->all();
    	// session()->forget('pagseguro_session_code');
	    $evento = Evento::find($request->evento_id);
		$total = null !== $request->input('valorFinal') ? $request->input('valorFinal') : $request->input('valorTotal');

        // dd($total);
        if($request->metodo == 'cartao'){

			$this->makePagSeguroSession();


	        return view('coordenador.programacao.cartao', compact('evento', 'total', 'data'));
        }else if($request->metodo == 'boleto'){

	        return view('coordenador.programacao.boleto', compact('evento', 'total', 'data'));
        }else{
        	return redirect()->back();
        }
    }

    public function obrigado()
    {
    	return view('coordenador.programacao.obrigado');
    }

    public function notifications()
	{

		try {
			$notifications = new Notification();
			$notifications = $notifications->getTransaction();
			dd($notifications);
			//Atualizar o pedido do usuario
			$reference = base64_decode($notifications->getReference());
			$pagamento = Pagamento::where('reference', $reference);
			$pagamento->update([
				'pagseguro_status' => $notifications->getStatus()
			]);

			//comentario sobre o pedido 
			if($notifications->getStatus() == 3){
				//Liberar o pedido do usuario..., atualizar o status do pedido para em separacao
				//Notificar o usuario que o pedido foi pago
				//Notificar a loja da confirmação dod pedio
			}
			return response()->json([], 204);
			
		} catch (Exception $e) {
			$message = env('APP_DEBUG') ? $e->getMessage() : [];

			return response()->json(['error' => $message], 500);
		}

	}

    public function proccess(Request $request)
    {	
    	
    	
    	try {
    		$dataPost = $request->all();
			$item = 'Inscricao';
			$user = Auth()->user();
			$reference = Uuid::uuid4();

			$creditCardPayment = new CreditCard($user, $item, $dataPost, $reference);

			$result = $creditCardPayment->doPayment();
		    
			// 'valor', 'descricao', 'reference', 'pagseguro_code', 'pagseguro_status', 'tipo_pagamento_id'
		    $pag = [
		    	'valor' =>  $dataPost['valorTotal'],	    	
		    	'descricao' =>  $item,	    	
		    	'reference' =>  $reference,	    	
		    	'pagseguro_code' =>  $result->getCode(),	    	
		    	'pagseguro_status' =>  $result->getStatus(),
		    	'tipo_pagamento_id' => 1    	
		    		    	
		    ];

		    $pagamento = new Pagamento();
		    $pagamento = $pagamento->create($pag);

		    $inscricao = [
		    	'user_id' => $dataPost['user_id'],
		    	'evento_id' => $dataPost['evento_id'],
		    	'pagamento_id' => $pagamento->id,
		    	'promocao_id' => isset( $dataPost['promocao_id'] ) ? $dataPost['promocao_id'] : null,
		    	'cupom_desconto_id' => isset( $dataPost['cupom'] ) ? $dataPost['cupom'] : null,
		    ];
		    $user->inscricaos()->create($inscricao);
		    

		    session()->forget('pagseguro_session_code');

		    return response()->json([
		    	'data' => [
		    		'status' => true,
		    		'message' => 'Inscricao concluída com sucesso!',
		    		'code' => $reference,

		    	]
		    ]);

    	} catch (Exception $e) {
    		$message = env('APP_DEBUG') ? $e->getMessage() : 'Erro ao processar inscrição!';
    		return response()->json([
		    	'data' => [
		    		'status' => false,
		    		'message' => $message,
		    		'code' => $reference,

		    	]
		    ], 401);
    	}


    }


    private function makePagSeguroSession()
    {

		if(!session()->has('pagseguro_session_code')) {
			$sessionCode = \PagSeguro\Services\Session::create(
				\PagSeguro\Configuration\Configure::getAccountCredentials()
			);

			
			return session()->put('pagseguro_session_code', $sessionCode->getResult());

		}
    }

    public function listarPagamentos($id)
    {
    	$evento = Evento::find($id);

    	$inscricaos = $evento->inscricaos;

    	return view('coordenador.programacao.pagamentos', compact('evento','inscricaos'));

    }

    public function pagBoleto(Request $request)
    {
		// dd($request->valorTotal);
		$user =  Auth()->user();
    	try {
		    $user = Auth()->user();
		    $pagseguro = PagSeguro::setReference('1')
		    ->setSenderInfo([
		       'senderName' => $user->name, //Deve conter nome e sobrenome
		       'senderPhone' => $user->celular, //Código de área enviado junto com o telefone
		       'senderEmail' => $user->email,
		       'senderHash' => $request->hash,
		       'senderCPF' => $user->cpf //Ou CPF se for Pessoa Física
		    ])
		    ->setShippingAddress([
		       'shippingAddressStreet' => 'Av. Lions',
		       'shippingAddressNumber' => '166',
		       'shippingAddressDistrict' => 'Centro',
		       'shippingAddressPostalCode' => '55325-000',
		       'shippingAddressCity' => 'Garanhuns',
		       'shippingAddressState' => 'PE'
		     ])
		     ->setItems([
		      [
		        'itemId' => '1',
		        'itemDescription' => 'Inscricao',
		        'itemAmount' => $request->valorTotal, //Valor unitário
		        'itemQuantity' => '1', // Quantidade de itens
		      ]
		    ])
		    ->send([
		      'paymentMethod' => 'boleto'
		    ]);

		    return response()->json([
		    	'data' => [
		    		'pagseguro' => $pagseguro,
		    	]
		    ], 200);
		}
		catch(\Artistas\PagSeguro\PagSeguroException $e) {
		     //codigo do erro
		    dd($e->getMessage(), $e->getCode());
		     //mensagem do erro
		}
    }


}