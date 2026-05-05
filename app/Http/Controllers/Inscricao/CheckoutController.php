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
use App\Services\PayPalService;
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

    public function telaPagamento(Request $request, Evento $evento)
    {
        $user = auth()->user();
        $inscricao = $evento->inscricaos()->where('user_id', $user->id)->first();
        $categoria = $inscricao?->categoria;

        if ($inscricao->pagamento != null) {
            return redirect()->route('checkout.statusPagamento', ['evento' => $evento->id]);
        }

        // Verifica se o usuário é estrangeiro (não tem CPF ou tem passaporte)
        $isEstrangeiro = empty($user->cpf) || !empty($user->passaporte);
        
        // Se já escolheu o gateway, redireciona para o pagamento
        if ($request->has('gateway')) {
            return $this->processarGateway($evento, $request->gateway);
        }

        return view('inscricao.pagamento.selecionar-gateway', compact('evento', 'inscricao', 'user', 'categoria', 'isEstrangeiro'));
    }

    private function processarGateway(Evento $evento, $gateway)
    {
        $user = auth()->user();
        $inscricao = $evento->inscricaos()->where('user_id', $user->id)->first();
        $categoria = $inscricao?->categoria;

        if ($gateway === 'paypal') {
            return $this->telaPagamentoPayPal($evento);
        } else {
            return $this->telaPagamentoMercadoPago($evento);
        }
    }

    public function telaPagamentoMercadoPago(Evento $evento)
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

    public function telaPagamentoPayPal(Evento $evento)
    {
        $user = auth()->user();
        $inscricao = $evento->inscricaos()->where('user_id', $user->id)->first();
        $categoria = $inscricao?->categoria;

        if ($inscricao->pagamento != null) {
            return redirect()->route('checkout.statusPagamento', ['evento' => $evento->id]);
        }

        try {
            $paypalService = new PayPalService();
            
            // O PayPal aceita BRL, então vamos usar BRL diretamente
            // Se você quiser converter para USD, pode usar uma API de conversão aqui
            $currency = 'BRL'; // Usar BRL diretamente, já que o valor está em reais
            $amount = (float) str_replace(',', '.', $categoria->valor_total);
            
            Log::info('PayPal: Criando ordem de pagamento', [
                'evento_id' => $evento->id,
                'user_id' => $user->id,
                'inscricao_id' => $inscricao->id,
                'amount' => $amount,
                'currency' => $currency,
                'valor_total_original' => $categoria->valor_total
            ]);
            
            $description = 'Inscrição no evento ' . $evento->nome;
            $returnUrl = route('checkout.paypal.success', ['evento' => $evento->id]);
            $cancelUrl = route('checkout.paypal.cancel', ['evento' => $evento->id]);

            $order = $paypalService->createOrder($amount, $currency, $description, $returnUrl, $cancelUrl, $inscricao->id);

            // Buscar link de aprovação
            $approveLink = collect($order['links'])->firstWhere('rel', 'approve')['href'] ?? null;

            if (!$approveLink) {
                throw new Exception('Erro ao criar ordem PayPal: link de aprovação não encontrado');
            }

            // Salvar ordem temporariamente na sessão
            session(['paypal_order_' . $evento->id => $order['id']]);

            return view('inscricao.pagamento.paypal', compact('evento', 'inscricao', 'user', 'categoria', 'approveLink', 'order'));

        } catch (\Exception $e) {
            Log::error('Erro ao criar ordem PayPal', [
                'message' => $e->getMessage(),
                'evento_id' => $evento->id,
                'user_id' => $user->id,
                'inscricao_id' => $inscricao->id ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('checkout.telaPagamento', ['evento' => $evento->id])
                ->withErrors(['msg' => 'Erro ao processar pagamento PayPal. Tente novamente ou entre em contato com o suporte.']);
        }
    }

    public function statusPagamento(Evento $evento)
    {
        $user = auth()->user();
        $inscricao = $evento->inscricaos()->where('user_id', $user->id)->first();
        $pagamento = $inscricao?->pagamento;
        
        if ($pagamento == null) {
            return redirect()->route('evento.visualizar', ['id' => $evento->id])->with('message', 'Não existe um pagamento para esse evento.');
        }

        // Se for PayPal, mostrar status diferente
        if ($pagamento->gateway === 'paypal') {
            return $this->statusPagamentoPayPal($evento, $pagamento);
        }

        // Mercado Pago
        $key = config('mercadopago.public_key');
        return view('inscricao.pagamento.status', compact('pagamento', 'key'));
    }

    public function statusPagamentoPayPal(Evento $evento, Pagamento $pagamento)
    {
        try {
            $paypalService = new PayPalService();
            $order = $paypalService->getOrder($pagamento->paypal_order_id);
            
            return view('inscricao.pagamento.status-paypal', compact('pagamento', 'order', 'evento'));
        } catch (\Exception $e) {
            Log::error('Erro ao obter status PayPal', [
                'message' => $e->getMessage(),
                'order_id' => $pagamento->paypal_order_id
            ]);
            
            return view('inscricao.pagamento.status-paypal', compact('pagamento', 'evento'));
        }
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
                'gateway' => 'mercadopago',
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
        // Verifica se é notificação do Mercado Pago ou PayPal
        $contents = $request->all();
        
        // Mercado Pago
        if (isset($contents["type"]) && $contents["type"] === "payment") {
            MercadoPagoConfig::setAccessToken(config('mercadopago.access_token'));
            $client = new PaymentClient();

            $payment = $client->get($contents["data"]["id"]);
            $pagamento = Pagamento::where('codigo', $contents["data"]["id"])->where('gateway', 'mercadopago')->first();
            
            if ($pagamento) {
                if ($payment->status == 'approved') {
                    $inscricao = $pagamento->inscricao;
                    $inscricao->finalizada = true;
                    $inscricao->save();
                    $evento = $inscricao->evento;

                    Mail::to($inscricao->user->email)->send(new EmailConfirmacaoPagamento($inscricao, $evento));
                }
                $pagamento->status = $payment->status;
                $pagamento->save();
            }
        }
        
        return response(status: 200);
    }

    /**
     * Callback de sucesso do PayPal
     */
    public function paypalSuccess(Request $request, Evento $evento)
    {
        $user = auth()->user();
        $inscricao = $evento->inscricaos()->where('user_id', $user->id)->first();

        $orderId = $request->get('token'); 
        $payerId = $request->input('PayerID');

        if (!$orderId) {
            $orderId = session('paypal_order_' . $evento->id);
        }
    
        if (!$orderId) {
            return redirect()->route('checkout.telaPagamento', $evento->id)
                ->withErrors(['msg' => 'Token de pagamento não retornado pelo PayPal.']);
        }

        try {
            $paypalService = new PayPalService();
            
            $capture = $paypalService->captureOrder($orderId);
            
            if (isset($capture['status']) && $capture['status'] === 'COMPLETED') {
                $purchaseUnit = $capture['purchase_units'][0];
                $amount = $purchaseUnit['payments']['captures'][0]['amount']['value'];
                
                // Criar ou atualizar pagamento
                $pagamento = Pagamento::where('paypal_order_id', $orderId)->first();
                
                if (!$pagamento) {
                    $pagamento = Pagamento::create([
                        'valor' => (float) $amount,
                        'descricao' => 'Inscrição no evento ' . $evento->nome,
                        'codigo' => $orderId,
                        'status' => 'approved',
                        'gateway' => 'paypal',
                        'paypal_order_id' => $orderId,
                        'paypal_payer_id' => $payerId,
                    ]);
                    
                    $inscricao->pagamento_id = $pagamento->id;
                    $inscricao->finalizada = true;
                    $inscricao->save();
                    
                    Mail::to($inscricao->user->email)->send(new EmailConfirmacaoPagamento($inscricao, $evento));
                    
                    Log::info('PayPal: Pagamento criado e inscrição finalizada', [
                        'pagamento_id' => $pagamento->id,
                        'inscricao_id' => $inscricao->id
                    ]);
                } else {
                    $pagamento->status = 'approved';
                    $pagamento->paypal_payer_id = $payerId;
                    $pagamento->save();
                    
                    if (!$inscricao->finalizada) {
                        $inscricao->finalizada = true;
                        $inscricao->save();
                        Mail::to($inscricao->user->email)->send(new EmailConfirmacaoPagamento($inscricao, $evento));
                        
                        Log::info('PayPal: Inscrição finalizada', [
                            'inscricao_id' => $inscricao->id
                        ]);
                    }
                }
                
                // Limpar sessão
                session()->forget('paypal_order_' . $evento->id);
                
                return redirect()->route('checkout.statusPagamento', ['evento' => $evento->id])
                    ->with('success', 'Pagamento realizado com sucesso!');
            } else {
                Log::warning('PayPal: Pagamento não completado', [
                    'order_id' => $orderId,
                    'status' => $capture['status'] ?? 'unknown',
                    'capture_response' => $capture
                ]);
                
                return redirect()->route('checkout.telaPagamento', ['evento' => $evento->id])
                    ->withErrors(['msg' => 'Pagamento não foi completado. Status: ' . ($capture['status'] ?? 'desconhecido')]);
            }
            
        } catch (\Exception $e) {
            Log::error('PayPal Success Callback Error: ' . $e->getMessage());
            return redirect()->route('checkout.telaPagamento', ['evento' => $evento->id])
                ->withErrors(['msg' => 'Erro interno ao processar a confirmação do PayPal.']);
            }
    }

    /**
     * Callback de cancelamento do PayPal
     */
    public function paypalCancel(Request $request, Evento $evento)
    {
        session()->forget('paypal_order_' . $evento->id);
        
        return redirect()->route('checkout.telaPagamento', ['evento' => $evento->id])
            ->with('message', 'Pagamento cancelado. Você pode tentar novamente.');
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
