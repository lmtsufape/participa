<?php

namespace App\Http\Controllers\Inscricao;

use App\Http\Controllers\Controller;
use App\Models\Inscricao\Pagamento;
use App\Models\Inscricao\TipoPagamento;
use App\Models\Submissao\Evento;
use App\Payment\PagSeguro\CartaoCredito;
use App\Payment\PagSeguro\Notification;
use Artistas\PagSeguro\PagSeguro;
use Artistas\PagSeguro\PagSeguroException;
use Exception;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

// use Ramsey\Uuid\Uuid;

class CheckoutController extends Controller
{

    public function telaPagamento(Evento $evento)
    {
        $key = env('MERCADOPAGO_PUBLIC_KEY');
        $user = auth()->user();
        $inscricao = $evento->inscricaos()->where('user_id', $user->id)->first();
        $categoria = $inscricao?->categoria;
        if ($inscricao->pagamento != null) {
            return redirect()->route('checkout.statusPagamento', ['evento' => $evento->id]);
        }

        return view('inscricao.pagamento.brick', compact('evento', 'inscricao', 'user', 'categoria', 'key'));
    }

    public function statusPagamento(Evento $evento)
    {
        $key = env('MERCADOPAGO_PUBLIC_KEY');
        $user = auth()->user();
        $inscricao = $evento->inscricaos()->where('user_id', $user->id)->first();
        $pagamento = $inscricao?->pagamento;
        if ($pagamento == null) {
            return redirect()->route('evento.visualizar', ['id' => $evento->id])->with('message', 'Não existe um pagamento para esse evento.');
        }

        return view('inscricao.pagamento.status', compact('pagamento', 'key'));
    }

    public function index(Request $request, $id)
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $data = $request->all();
        // session()->forget('pagseguro_session_code');
        $evento = Evento::find($request->evento_id);
        $total = null !== $request->input('valorFinal') ? $request->input('valorFinal') : $request->input('valorTotal');

        // dd($total);
        if ($request->metodo == 'cartao') {
            $this->makePagSeguroSession();

            return view('coordenador.programacao.cartao', compact('evento', 'total', 'data'));
        } elseif ($request->metodo == 'boleto') {
            return view('coordenador.programacao.boleto', compact('evento', 'total', 'data'));
        } else {
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
                'pagseguro_status' => $notifications->getStatus(),
            ]);

            //comentario sobre o pedido
            if ($notifications->getStatus() == 3) {
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

            $creditCardPayment = new CartaoCredito($user, $item, $dataPost, $reference);

            $result = $creditCardPayment->doPayment();

            // 'valor', 'descricao', 'reference', 'pagseguro_code', 'pagseguro_status', 'tipo_pagamento_id'
            $pag = [
                'valor' => $dataPost['valorTotal'],
                'descricao' => $item,
                'reference' => $reference,
                'pagseguro_code' => $result->getCode(),
                'pagseguro_status' => $result->getStatus(),
                'tipo_pagamento_id' => 1,
            ];

            $pagamento = new Pagamento();
            $pagamento = $pagamento->create($pag);

            $inscricao = [
                'user_id' => $dataPost['user_id'],
                'evento_id' => $dataPost['evento_id'],
                'pagamento_id' => $pagamento->id,
                'promocao_id' => isset($dataPost['promocao_id']) ? $dataPost['promocao_id'] : null,
                'cupom_desconto_id' => isset($dataPost['cupom']) ? $dataPost['cupom'] : null,
            ];
            $user->inscricaos()->create($inscricao);

            session()->forget('pagseguro_session_code');

            return response()->json([
                'data' => [
                    'status' => true,
                    'message' => 'Inscricao concluída com sucesso!',
                    'code' => $reference,
                ],
            ]);
        } catch (Exception $e) {
            $message = env('APP_DEBUG') ? $e->getMessage() : 'Erro ao processar inscrição!';

            return response()->json([
                'data' => [
                    'status' => false,
                    'message' => $message,
                    'code' => $reference,
                ],
            ], 401);
        }
    }

    private function makePagSeguroSession()
    {
        if (! session()->has('pagseguro_session_code')) {
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

        return view('coordenador.programacao.pagamentos', compact('evento', 'inscricaos'));
    }

    public function pagBoleto(Request $request)
    {
        // dd($request->valorTotal);
        $user = Auth()->user();
        try {
            $user = Auth()->user();
            $cpf = str_replace('.', '', $user->cpf);
            $cpf = str_replace('-', '', $cpf);
            $pagseguro = PagSeguro::setReference('1')
                ->setSenderInfo([
                    'senderName' => $user->name, //Deve conter nome e sobrenome
                    'senderPhone' => $user->celular, //Código de área enviado junto com o telefone
                    'senderEmail' => $user->email,
                    'senderHash' => $request->hash,
                    'senderCPF' => $cpf, //Ou CPF se for Pessoa Física
                ])
                ->setShippingAddress([
                    'shippingAddressStreet' => 'Av. Lions',
                    'shippingAddressNumber' => '166',
                    'shippingAddressDistrict' => 'Centro',
                    'shippingAddressPostalCode' => '55325-000',
                    'shippingAddressCity' => 'Garanhuns',
                    'shippingAddressState' => 'PE',
                ])
                ->setItems([
                    [
                        'itemId' => '1',
                        'itemDescription' => 'Inscricao',
                        'itemAmount' => $request->valorTotal, //Valor unitário
                        'itemQuantity' => '1', // Quantidade de itens
                    ],
                ])
                ->send([
                    'paymentMethod' => 'boleto',
                ]);

            return response()->json([
                'data' => [
                    'pagseguro' => $pagseguro,
                ],
            ], 200);
        } catch (PagSeguroException $e) {
            //codigo do erro
            dd($e->getMessage(), $e->getCode());
            //mensagem do erro
        }
    }

    private function cartao(Request $request)
    {
        \MercadoPago\SDK::setAccessToken(env('MERCADOPAGO_ACCESS_TOKEN'));
        $contents = $request->all();
        $evento = Evento::find($contents['evento']);
        $user = auth()->user();
        $inscricao = $evento->inscricaos()->where('user_id', $user->id)->first();
        $categoria = $inscricao->categoria;

        $payment = new \MercadoPago\Payment();
        $payment->transaction_amount = $categoria->valor_total;
        $payment->token = $contents['token'];
        $payment->installments = $contents['installments'];
        $payment->payment_method_id = $contents['payment_method_id'];
        $payment->issuer_id = $contents['issuer_id'];
        $payer = new \MercadoPago\Payer();
        $payer->email = $contents['payer']['email'];
        $payer->identification = array(
            "type" => $contents['payer']['identification']['type'],
            "number" => $contents['payer']['identification']['number'],
        );
        $payment->payer = $payer;
        $payment->save();
        $response = array(
            'status' => $payment->status,
            'status_detail' => $payment->status_detail,
            'id' => $payment->id,
        );
        $tipo_pagamento = TipoPagamento::where('descricao', 'cartao')->first();
        $descricao = 'Inscrição no evento '.$evento->nome.' com valor de '.$categoria->valor_total;
        $pagamento = Pagamento::create([
            'valor' => $categoria->valor_total,
            'tipo_pagamento_id' => $tipo_pagamento->id,
            'descricao' => $descricao,
            'codigo' => $payment->id,
            'status' => $payment->status,
        ]);
        $inscricao->pagamento_id = $pagamento->id;
        $inscricao->save();
        return redirect()->route('checkout.statusPagamento', ['evento' => $evento->id]);
    }

    private function pix(Request $request)
    {
        \MercadoPago\SDK::setAccessToken(env('MERCADOPAGO_ACCESS_TOKEN'));
        $evento = Evento::find($request->evento);
        $user = auth()->user();
        $inscricao = $evento->inscricaos()->where('user_id', $user->id)->first();
        $categoria = $inscricao->categoria;
        $descricao = 'Inscrição no evento '.$evento->nome.' com valor de '.$categoria->valor_total;
        $contents = $request->all();

        $payment = new \MercadoPago\Payment();
        $payment->transaction_amount = $categoria->valor_total;
        $payment->description = $descricao;
        $payment->payment_method_id = "pix";
        $payment->payer = array(
            "email" => $contents['payer']['email'],
            "first_name" => $user->name,
            "last_name" => "User",
            "identification" => array(
                "type" => "CPF",
                "number" => $user->cpf,
            ),
            "address" =>  array(
                "zip_code" => $user->endereco->cep,
                "street_name" => $user->endereco->rua,
                "street_number" => $user->endereco->numero,
                "neighborhood" => $user->endereco->bairro,
                "city" => $user->endereco->cidado,
                "federal_unit" => $user->endereco->uf,
            ),
        );

        $payment->save();
        $response = array(
            'status' => $payment->status,
            'status_detail' => $payment->status_detail,
            'id' => $payment->id,
        );
        $tipo_pagamento = TipoPagamento::where('descricao', 'pix')->first();
        $descricao = 'Inscrição no evento '.$evento->nome.' com valor de '.$categoria->valor_total;
        $pagamento = Pagamento::create([
            'valor' => $categoria->valor_total,
            'tipo_pagamento_id' => $tipo_pagamento->id,
            'descricao' => $descricao,
            'codigo' => $payment->id,
            'status' => $payment->status,
        ]);
        $inscricao->pagamento_id = $pagamento->id;
        $inscricao->save();
        return redirect()->route('checkout.statusPagamento', ['evento' => $evento->id]);
    }

    private function boleto(Request $request)
    {
        \MercadoPago\SDK::setAccessToken(env('MERCADOPAGO_ACCESS_TOKEN'));
        $contents = $request->all();
        $evento = Evento::find($contents['evento']);
        $user = auth()->user();
        $inscricao = $evento->inscricaos()->where('user_id', $user->id)->first();
        $categoria = $inscricao->categoria;

        $payment = new \MercadoPago\Payment();
        $payment->transaction_amount = $contents['transaction_amount'];
        $descricao = 'Inscrição no evento '.$evento->nome.' com valor de '.$categoria->valor_total;
        $payment->description = $descricao;
        $payment->payment_method_id = $contents['payment_method_id'];

        $payment->payer = array(
            "email" =>  $contents['payer']['email'],
            "first_name" => $contents['payer']['first_name'],
            "last_name" => $contents['payer']['last_name'],
            "identification" => array(
                "type" => $contents['payer']['identification']['type'],
                "number" => $contents['payer']['identification']['number'],
            ),
            "address"=>  array(
                "zip_code" => $contents['payer']['address']['zip_code'],
                "street_name" => $contents['payer']['address']['street_name'],
                "street_number" => $contents['payer']['address']['street_number'],
                "neighborhood" => $contents['payer']['address']['neighborhood'],
                "city" => $contents['payer']['address']['city'],
                "federal_unit" => $contents['payer']['address']['federal_unit'],
            ),
        );
        $payment->save();
        $response = array(
            'status' => $payment->status,
            'status_detail' => $payment->status_detail,
            'id' => $payment->id,
        );
        $tipo_pagamento = TipoPagamento::where('descricao', 'boleto')->first();
        $descricao = 'Inscrição no evento '.$evento->nome.' com valor de '.$categoria->valor_total;
        $pagamento = Pagamento::create([
            'valor' => $categoria->valor_total,
            'tipo_pagamento_id' => $tipo_pagamento->id,
            'descricao' => $descricao,
            'codigo' => $payment->id,
            'status' => $payment->status,
        ]);
        $inscricao->pagamento_id = $pagamento->id;
        $inscricao->save();
        return redirect()->route('checkout.statusPagamento', ['evento' => $evento->id]);
    }

    public function processPayment(Request $request)
    {
        switch ($request['payment_method_id']) {
            case 'pix':
                return $this->pix($request);
            case 'bolbradesco':
            case 'pec':
                return $this->boleto($request);
            default:
                return $this->cartao($request);
        }
    }
}
