<?php

namespace App\Console\Commands;

use App\Services\PayPalService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestPayPalConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paypal:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa a conexão com o PayPal';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Testando conexão com PayPal...');
        $this->newLine();
        
        // Verificar configurações
        $clientId = trim(config('paypal.client_id'));
        $clientSecret = trim(config('paypal.client_secret'));
        $mode = config('paypal.mode', 'sandbox');
        
        $this->info("Modo: {$mode}");
        $this->info("Client ID: {$clientId}");
        $this->info("Client ID (tamanho): " . strlen($clientId) . " caracteres");
        $this->info("Client Secret (tamanho): " . (empty($clientSecret) ? 'VAZIO' : strlen($clientSecret) . ' caracteres'));
        $this->info("Client Secret (primeiros 10): " . substr($clientSecret, 0, 10) . "...");
        $this->newLine();
        
        if (empty($clientId) || empty($clientSecret)) {
            $this->error('✗ Credenciais não configuradas!');
            return 1;
        }
        
        // Verificar se há caracteres especiais ou espaços
        if (preg_match('/\s/', $clientId)) {
            $this->warn('⚠ AVISO: Client ID contém espaços!');
        }
        if (preg_match('/\s/', $clientSecret)) {
            $this->warn('⚠ AVISO: Client Secret contém espaços!');
        }
        
        // Testar Base64 encoding
        $credentials = base64_encode($clientId . ':' . $clientSecret);
        $this->info("Base64 credentials (primeiros 30): " . substr($credentials, 0, 30) . "...");
        $this->newLine();
        
        $this->info('Tentando obter access token...');
        $this->newLine();
        
        try {
            $paypalService = new PayPalService();
            
            $token = $paypalService->getAccessToken();
            
            if ($token) {
                $this->info('✓ Access token obtido com sucesso!');
                $this->info('Token: ' . substr($token, 0, 30) . '...');
                return 0;
            } else {
                $this->error('✗ Falha ao obter access token');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('✗ Erro: ' . $e->getMessage());
            $this->newLine();
            $this->warn('Possíveis causas:');
            $this->line('1. Credenciais incorretas ou expiradas');
            $this->line('2. Credenciais de ambiente diferente (sandbox vs live)');
            $this->line('3. Conta PayPal sandbox com problemas');
            $this->newLine();
            $this->info('Soluções sugeridas:');
            $this->line('1. Acesse https://developer.paypal.com');
            $this->line('2. Vá em "My Apps & Credentials"');
            $this->line('3. Certifique-se de estar em modo SANDBOX');
            $this->line('4. Clique no app "Default Application"');
            $this->line('5. Clique em "Show" no Secret e copie novamente');
            $this->line('6. Se necessário, gere um novo Secret clicando em "Regenerate Secret"');
            $this->line('7. Atualize o arquivo .env com as novas credenciais');
            $this->line('8. Execute: php artisan config:clear');
            $this->newLine();
            $this->warn('Verifique os logs em storage/logs/laravel.log para mais detalhes');
            return 1;
        }
    }
}

