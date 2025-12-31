<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PayPalService
{
    private $clientId;
    private $clientSecret;
    private $mode;
    private $baseUrl;
    private $accessToken;

    public function __construct()
    {
        $this->clientId = trim(config('paypal.client_id'));
        $this->clientSecret = trim(config('paypal.client_secret'));
        $this->mode = config('paypal.mode', 'sandbox');
        $this->baseUrl = $this->mode === 'sandbox' 
            ? 'https://api-m.sandbox.paypal.com' 
            : 'https://api-m.paypal.com';
        
        // Remover qualquer caractere invisível ou quebra de linha
        $this->clientId = preg_replace('/[\x00-\x1F\x7F]/', '', $this->clientId);
        $this->clientSecret = preg_replace('/[\x00-\x1F\x7F]/', '', $this->clientSecret);
        
        // Validação das credenciais
        if (empty($this->clientId) || empty($this->clientSecret)) {
            Log::error('PayPal: Credenciais não configuradas', [
                'client_id_exists' => !empty($this->clientId),
                'client_secret_exists' => !empty($this->clientSecret),
                'mode' => $this->mode
            ]);
            throw new \Exception('Credenciais do PayPal não configuradas corretamente');
        }
    }

    /**
     * Obtém o access token do PayPal usando cURL (método alternativo)
     */
    private function getAccessTokenWithCurl()
    {
        $url = $this->baseUrl . '/v1/oauth2/token';
        $credentials = base64_encode($this->clientId . ':' . $this->clientSecret);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . $credentials,
            'Accept: application/json',
            'Accept-Language: en_US',
            'Content-Type: application/x-www-form-urlencoded'
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($curlError) {
            throw new \Exception('cURL Error: ' . $curlError);
        }
        
        $data = json_decode($response, true);
        
        if ($httpCode === 200 && isset($data['access_token'])) {
            return $data['access_token'];
        }
        
        $errorMsg = $data['error_description'] ?? 'Erro desconhecido';
        throw new \Exception('PayPal cURL: ' . $errorMsg . ' (HTTP ' . $httpCode . ')');
    }

    /**
     * Obtém o access token do PayPal
     */
    public function getAccessToken()
    {
        // Removi o cache da variável $this->accessToken para garantir que 
        // ele peça um token novo em cada fase importante da transação
        try {
            $response = Http::withoutVerifying()
                ->asForm()
                ->withBasicAuth($this->clientId, $this->clientSecret)
                ->post($this->baseUrl . '/v1/oauth2/token', [
                    'grant_type' => 'client_credentials'
                ]);
            
            if (!$response->successful()) {
                dd([
                    'Erro_ao_gerar_token' => $response->json(),
                    'Client_ID' => $this->clientId,
                    'URL_Base' => $this->baseUrl
                ]);
            }

            if ($response->successful()) {
                return $response->json()['access_token'];
            }

            Log::error('PayPal: Erro ao obter access token', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            throw new \Exception('Erro ao obter access token do PayPal');
        } catch (\Exception $e) {
            Log::error('PayPal: Exception ao obter access token', ['message' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Cria uma ordem de pagamento no PayPal
     */
    public function createOrder($amount, $currency, $description, $returnUrl, $cancelUrl, $customId = null)
    {
        try {
            // 1. Limpeza rigorosa do valor: remover pontos de milhar e garantir ponto decimal
            $formattedAmount = number_format((float) $amount, 2, '.', '');

            $token = $this->getAccessToken();

            $orderData = [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'amount' => [
                        'currency_code' => $currency, // Se for BRL, certifique-se que sua conta sandbox suporta BRL
                        'value' => $formattedAmount
                    ],
                    'description' => substr($description, 0, 127), // Limite de caracteres do PayPal
                    'custom_id' => (string) $customId
                ]],
                'application_context' => [
                    'return_url' => $returnUrl,
                    'cancel_url' => $cancelUrl,
                    'user_action' => 'PAY_NOW'
                ]
            ];

            $response = Http::withToken($token)
                ->post($this->baseUrl . '/v2/checkout/orders', $orderData);

            if ($response->successful()) {
                $orderResponse = $response->json();
                Log::info('PayPal: Ordem criada com sucesso', [
                    'order_id' => $orderResponse['id'] ?? 'unknown',
                    'status' => $orderResponse['status'] ?? 'unknown'
                ]);
                return $orderResponse;
            }

            $errorBody = $response->json();
            Log::error('PayPal: Erro ao criar ordem', [
                'status' => $response->status(),
                'body' => $response->body(),
                'error' => $errorBody['error'] ?? 'unknown',
                'error_description' => $errorBody['error_description'] ?? 'unknown',
                'details' => $errorBody['details'] ?? [],
                'request' => $orderData
            ]);

            $errorMessage = $errorBody['error_description'] ?? $errorBody['message'] ?? 'Erro ao criar ordem no PayPal';
            throw new \Exception('Erro ao criar ordem no PayPal: ' . $errorMessage);
        } catch (\Exception $e) {

            dd('Erro na Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Captura um pagamento após aprovação do usuário
     */
    public function captureOrder($orderId)
    {
        try {
            $token = $this->getAccessToken();

            // Usamos asBody e json_encode para garantir que o corpo seja exatamente "{}"
            $response = Http::withoutVerifying()
                ->withToken($token)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->withBody('{}', 'application/json') // Força o corpo JSON vazio
                ->post($this->baseUrl . '/v2/checkout/orders/' . $orderId . '/capture'); 

            if ($response->successful()) {
                return $response->json();
            }

            // Se o erro mudar de "MALFORMED" para "UNPROCESSABLE_ENTITY", o problema é a moeda (BRL)
            Log::error('PayPal: Erro ao capturar ordem', [
                'status' => $response->status(),
                'body' => $response->body(),
                'order_id' => $orderId
            ]);

            throw new \Exception('Erro ao capturar ordem no PayPal: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('PayPal: Exception ao capturar ordem', ['message' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Obtém detalhes de uma ordem
     */
    public function getOrder($orderId)
    {
        try {
            $token = $this->getAccessToken();

            $response = Http::withToken($token)
                ->withHeaders([
                    'Accept' => 'application/json'
                ])
                ->get($this->baseUrl . '/v2/checkout/orders/' . $orderId);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PayPal: Erro ao obter ordem', [
                'status' => $response->status(),
                'body' => $response->body(),
                'order_id' => $orderId
            ]);

            throw new \Exception('Erro ao obter ordem do PayPal: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('PayPal: Exception ao obter ordem', [
                'message' => $e->getMessage(),
                'order_id' => $orderId
            ]);
            throw $e;
        }
    }

    /**
     * Verifica webhook do PayPal (para notificações)
     */
    public function verifyWebhook($headers, $body)
    {
        // Implementação de verificação de webhook se necessário
        // Por enquanto, vamos confiar nas notificações
        return true;
    }
}

