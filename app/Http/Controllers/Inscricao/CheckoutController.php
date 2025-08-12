<?php

namespace App\Http\Controllers\Inscricao;

use App\Http\Controllers\Controller;
use App\Mail\EmailConfirmacaoPagamento;
use Illuminate\Support\Facades\Mail;
use App\Models\Inscricao\CategoriaParticipante;
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
use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use MercadoPago\Exceptions\MPApiException;
use Throwable;
use Carbon\Carbon;

// use Ramsey\Uuid\Uuid;

class CheckoutController extends Controller
{

    public function telaPagamento(Evento $evento)
    {
        $key = config('mercadopago.public_key');
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
        $key = config('mercadopago.public_key');
        $user = auth()->user();
        $inscricao = $evento->inscricaos()->where('user_id', $user->id)->first();
        $pagamento = $inscricao?->pagamento;
        if ($pagamento == null) {
            return redirect()->route('evento.visualizar', ['id' => $evento->id])->with('message', 'Não existe um pagamento para esse evento.');
        }

        return view('inscricao.pagamento.status', compact('pagamento', 'key'));
    }

    public function listarPagamentos($id)
    {
        $evento = Evento::find($id);

        $inscricaos = $evento->inscricaos;

        return view('coordenador.programacao.pagamentos', compact('evento', 'inscricaos'));
    }

    public function processPayment(Request $request)
    {
        MercadoPagoConfig::setAccessToken(config('mercadopago.access_token'));
        $client = new PaymentClient();

        $contents = $request->all();
        $evento = Evento::find($contents['evento']);
        $user = auth()->user();
        $inscricao = $evento->inscricaos()->where('user_id', $user->id)->first();
        $categoria = $inscricao->categoria;
        $descricao = 'Inscrição no evento '.$evento->nome.' com valor de '.$categoria->valor_total;

        $request = $this->gerarRequest($contents, $categoria);

        $request_options = new RequestOptions();
        $request_options->setCustomHeaders(["X-Idempotency-Key: ".Str::uuid()]);

        try {
            $payment = $client->create($request, $request_options);
            // $tipo_pagamento = TipoPagamento::where('descricao', $contents['payment_method_id'])->first();
            $descricao = 'Inscrição no evento '.$evento->nome.' com valor de '.$categoria->valor_total;
            $pagamento = Pagamento::create([
                'valor' => (float) $categoria->valor_total,
                // 'tipo_pagamento_id' => $tipo_pagamento->id,
                'descricao' => $descricao,
                'codigo' => $payment->id,
                'status' => $payment->status,
            ]);
            $inscricao->pagamento_id = $pagamento->id;
            $inscricao->save();
            return redirect()->route('checkout.statusPagamento', ['evento' => $evento->id]);
        } catch (MPApiException $e) {
            Log::error('MPApiException: Erro em operação de pagamento com'.$contents['payment_method_id'], [
                'status_code' => $e->getApiResponse()->getStatusCode(),
                'content' => $e->getApiResponse()->getContent(),
            ]);
        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage());
        } catch (Throwable $e) {
            Log::error('Erro em operação de pagamento com'.$contents['payment_method_id'], [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors(['msg' => 'Ocorreu um erro ao tentar realizar o pagamento, tente novamente.']);
        }
    }

    private function gerarRequest($contents, CategoriaParticipante $categoria)
    {;
        // dd($contents);
        $request = [];
        switch ($contents['payment_method_id']) {
            case 'pix':
                $request = [
                    "transaction_amount" => (float) $contents['transaction_amount'],
                    "payment_method_id" => "pix",
                    "notification_url" => route('checkout.notifications'),
                    "payer" => [
                        "email" => $contents['payer']['email'],
                    ],
                ];
                break;
            case 'bolbradesco':
                $request = [
                    "transaction_amount" => (float) $contents['transaction_amount'],
                    // "description" => $contents['description'],
                    "payment_method_id" => $contents['payment_method_id'],
                    "notification_url" => route('checkout.notifications'),
                    "payer" => [
                        "email" =>  $contents['payer']['email'],
                        "first_name" => $contents['payer']['first_name'],
                        "last_name" => $contents['payer']['last_name'],
                        "identification" => [
                            "type" => $contents['payer']['identification']['type'],
                            "number" => $contents['payer']['identification']['number'],
                        ],
                        "address"=>  [
                            "zip_code" => $contents['payer']['address']['zip_code'],
                            "street_name" => $contents['payer']['address']['street_name'],
                            "street_number" => $contents['payer']['address']['street_number'],
                            "neighborhood" => $contents['payer']['address']['neighborhood'],
                            "city" => $contents['payer']['address']['city'],
                            "federal_unit" => $contents['payer']['address']['federal_unit'],
                        ],

                    ],
                    "date_of_expiration"   => Carbon::now('America/Recife')
                                            ->addDays(10)
                                            ->format('Y-m-d\TH:i:s.000-03:00'),
                ];
                break;
            case 'master':
            case 'amex':
            case 'cabal':
            case 'hipercard':
            case 'elo':
            case 'visa':
                $request = [
                    "transaction_amount" => (float) $categoria->valor_total,
                    "token" => $contents['token'],
                    "installments" => $contents['installments'],
                    "payment_method_id" => $contents['payment_method_id'],
                    "issuer_id" => $contents['issuer_id'],
                    "notification_url" => route('checkout.notifications'),
                    "payer" => [
                        "email" => $contents['payer']['email'],
                        "identification" => [
                            "type" => $contents['payer']['identification']['type'],
                            "number" => $contents['payer']['identification']['number'],
                        ],
                    ],
                ];
                break;
            default:
                throw new Exception('Método de pagamento não suportado: '.$contents['payment_type_id']);
        }
        return $request;
    }

    public function notifications(Request $request)
    {
        MercadoPagoConfig::setAccessToken(config('mercadopago.access_token'));
        $client = new PaymentClient();

        $contents = $request->all();
        switch($contents["type"]) {
            case "payment":
                $payment = $client->get($contents["data"]["id"]);
                $pagamento = Pagamento::where('codigo', $contents["data"]["id"])->first();

                $fee = 0.0;
                try {
                    $paymentArr = json_decode(json_encode($payment), true) ?: [];

                    $gross = (float) data_get($paymentArr, 'transaction_amount', 0);

                    foreach ((array) data_get($paymentArr, 'fee_details', []) as $fd) {
                        if (($fd['fee_payer'] ?? null) === 'collector') {
                            $fee += (float) ($fd['amount'] ?? 0);
                        }
                    }

                    if ($fee <= 0) {
                        $netReceived = (float) data_get($paymentArr, 'transaction_details.net_received_amount', 0);
                        $fee = max(0.0, $gross - $netReceived);
                    }

                    if ($fee <= 0) {
                        foreach ((array) data_get($paymentArr, 'charges_details', []) as $ch) {
                            if (($ch['type'] ?? null) === 'fee' && ($ch['name'] ?? null) === 'mercadopago_fee') {
                                $original = (float) data_get($ch, 'amounts.original', 0);
                                $refunded = (float) data_get($ch, 'amounts.refunded', 0);
                                $fee += max(0.0, $original - $refunded);
                            }
                        }
                    }

                    if ($pagamento) {
                        $pagamento->taxa = round($fee, 2);
                    }
                } catch (\Throwable $e) {

                    logger()->warning('Falha ao calcular taxa MP', [
                        'payment_id' => $contents["data"]["id"] ?? null,
                        'error'      => $e->getMessage(),
                    ]);
                }

                if ($payment->status == 'approved') {
                    $inscricao = $pagamento->inscricao;
                    $inscricao->finalizada = true;
                    $inscricao->save();
                    $evento = $inscricao->evento;

                    Mail::to($inscricao->user->email)->send(new EmailConfirmacaoPagamento($inscricao, $evento));
                }
                $pagamento->status = $payment->status;
                $pagamento->save();
                break;
            case "plan":
            case "subscription":
            case "invoice":
            case "point_integration_wh":
                break;
        }
        return response(status: 200);
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
            // dd($e->getMessage(), $e->getCode());
            //mensagem do erro
        }
    }

}
