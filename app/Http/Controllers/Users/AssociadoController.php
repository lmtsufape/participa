<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anuidade;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;
use App\Services\PayPalService;
use MercadoPago\Client\Common\RequestOptions;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AssociadoController extends Controller
{
    public function index() {
        return view('user.associar');
    }

    public function pagar(Request $request, $tipo)
    {
        $user = auth()->user()->load('endereco');
        
        $valor = ($tipo === 'profissional') ? 160.00 : 80.00;

        if ($request->has('gateway')) {
            if ($request->gateway === 'paypal') {
                return $this->pagarComPayPal($tipo, $valor);
            } else {
                return $this->pagarComMercadoPago($user, $tipo, $valor);
            }
        }

        $isEstrangeiro = empty($user->cpf) || !empty($user->passaporte);

        return view('user.selecionar_gateway_associacao', compact('user', 'tipo', 'valor', 'isEstrangeiro'));
    }

    private function pagarComMercadoPago($user, $tipo, $valor)
    {
        $key = config('mercadopago.public_key');
        return view('user.pagar_associacao', compact('user', 'key', 'tipo', 'valor'));
    }

    private function pagarComPayPal($tipo, $valor)
    {
        $user = auth()->user();

        try {
            $paypalService = new PayPalService();
            $currency = 'BRL';
            $amount = (float) $valor;

            Log::info('PayPal Associação: Criando ordem de pagamento', [
                'user_id' => $user->id,
                'tipo' => $tipo,
                'amount' => $amount
            ]);

            $description = 'Anuidade Associação CPFreire - ' . ucfirst($tipo);
            
            $returnUrl = route('associar.paypal.success', ['tipo' => $tipo]);
            $cancelUrl = route('associar.paypal.cancel', ['tipo' => $tipo]);

            $order = $paypalService->createOrder($amount, $currency, $description, $returnUrl, $cancelUrl, 'assoc_' . $user->id);

            $approveLink = collect($order['links'])->firstWhere('rel', 'approve')['href'] ?? null;

            if (!$approveLink) {
                throw new Exception('Erro ao criar ordem PayPal para associação: link de aprovação não encontrado');
            }

            session(['paypal_assoc_order_' . $user->id => $order['id']]);

            return view('user.pagar_paypal_associacao', compact('tipo', 'valor', 'approveLink', 'order'));

        } catch (\Exception $e) {
            Log::error('Erro ao criar ordem PayPal para associação', [
                'message' => $e->getMessage(),
                'user_id' => $user->id
            ]);

            return redirect()->route('associar.pagar', ['tipo' => $tipo])
                ->withErrors(['msg' => 'Erro ao processar pagamento com PayPal. Tente novamente ou use o Mercado Pago.']);
        }
    }

    public function paypalSuccess(Request $request, $tipo)
    {
        $user = auth()->user();
        $orderId = $request->get('token');
        $payerId = $request->input('PayerID');

        if (!$orderId) {
            $orderId = session('paypal_assoc_order_' . $user->id);
        }

        if (!$orderId) {
            return redirect()->route('associar.pagar', $tipo)
                ->withErrors(['msg' => 'Token de pagamento de associação inválido ou expirado.']);
        }

        try {
            $paypalService = new PayPalService();
            $capture = $paypalService->captureOrder($orderId);

            if (isset($capture['status']) && $capture['status'] === 'COMPLETED') {
                
                $jaExiste = Anuidade::where('pagamento_id', $orderId)->exists();

                if (!$jaExiste) {
                    Anuidade::create([
                        'user_id' => $user->id,
                        'pagamento_id' => $orderId, // Armazena a ordem do PayPal como id do pagamento
                        'tipo' => $tipo,
                        'ano_referencia' => date('Y'),
                        'validade' => now()->addYear(),
                        'status' => 'approved', // PayPal captura imediatamente como aprovado
                    ]);

                    Log::info('Anuidade via PayPal aprovada e registrada com sucesso', [
                        'user_id' => $user->id,
                        'order_id' => $orderId
                    ]);
                }

                session()->forget('paypal_assoc_order_' . $user->id);

                return redirect()->to('/perfil?sucesso=associado')
                    ->with('success', 'Associação realizada com sucesso!');
            } else {
                return redirect()->route('associar.pagar', $tipo)
                    ->withErrors(['msg' => 'O pagamento não foi completamente aprovado no PayPal.']);
            }

        } catch (\Exception $e) {
            Log::error('Erro no callback de sucesso do PayPal (Associação): ' . $e->getMessage());
            return redirect()->route('associar.pagar', $tipo)
                ->withErrors(['msg' => 'Erro interno ao confirmar seu pagamento no PayPal.']);
        }
    }

    public function paypalCancel(Request $request, $tipo)
    {
        $user = auth()->user();
        session()->forget('paypal_assoc_order_' . $user->id);

        return redirect()->route('associar.pagar', $tipo)
            ->with('message', 'Processo de pagamento cancelado pelo usuário.');
    }

    public function processarPagamentoAssociado(Request $request) {
        MercadoPagoConfig::setAccessToken(config('mercadopago.access_token'));
        $client = new PaymentClient();

        $contents = $request->all();
        $tipo = $contents['tipo_associado'];
        $valor = ($tipo == 'estudante') ? 80.00 : 160.00;

        $paymentRequest = [
            "transaction_amount" => (float) $valor,
            "token" => $contents['token'],
            "installments" => (int) $contents['installments'],
            "payment_method_id" => $contents['payment_method_id'],
            "issuer_id" => $contents['issuer_id'],
            "payer" => [
                "email" => $contents['payer']['email'],
                "identification" => [
                    "type" => $contents['payer']['identification']['type'],
                    "number" => $contents['payer']['identification']['number'],
                ],
            ],
        ];

        $request_options = new RequestOptions();
        $request_options->setCustomHeaders(["X-Idempotency-Key: ".Str::uuid()]);

        try {
            $payment = $client->create($paymentRequest, $request_options);

            if ($payment->status == 'approved') {
                Anuidade::create([
                    'user_id' => auth()->id(),
                    'pagamento_id' => $payment->id,
                    'tipo' => $tipo,
                    'ano_referencia' => date('Y'),
                    'validade' => now()->addYear(),
                ]);

                Log::info('Anuidade aprovada e registrada com sucesso', [
                    'user_id' => auth()->id(),
                    'payment_id' => $payment->id
                ]);
                return response()->json(['status' => 'success']);
            }

            Log::warning('Pagamento de anuidade não aprovado imediatamente', [
                'user_id' => auth()->id(),
                'payment_id' => $payment->id,
                'status' => $payment->status,
                'status_detail' => $payment->status_detail
            ]);

            return response()->json(['status' => $payment->status]);

        } catch (MPApiException $e) {
            Log::error('Erro na API do Mercado Pago ao processar anuidade', [
                'user_id' => auth()->id(),
                'status_code' => $e->getApiResponse()->getStatusCode(),
                'content' => $e->getApiResponse()->getContent(),
            ]);
            return response()->json(['error' => 'Erro ao processar com provedor de pagamento.'], 400);

        } catch (\Exception $e) {
            Log::error('Exceção geral no processamento de anuidade', [
                'user_id' => auth()->id(),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json(['error' => 'Ocorreu um erro interno.'], 500);
        }
    }
}