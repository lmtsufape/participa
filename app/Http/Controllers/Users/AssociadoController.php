<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anuidade;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Common\RequestOptions;
use Illuminate\Support\Str;

class AssociadoController extends Controller
{
    public function index() {
        return view('user.associar');
    }

    public function pagar($tipo)
    {
        $user = auth()->user()->load('endereco');
        $key = config('mercadopago.public_key');
        
        // Definir valor baseado no tipo
        $valor = ($tipo === 'profissional') ? 160.00 : 80.00;

        return view('user.pagar_associacao', compact('user', 'key', 'tipo', 'valor'));
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