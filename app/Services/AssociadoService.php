<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class AssociadoService
{
    protected string $baseUrl;
    protected string $token;

    public function __construct()
    {
        $this->baseUrl = config('services.associados.url');
        $this->token   = config('services.associados.token');
    }

    /**
     * Consulta a API por CPF usando POST (json).
     *
     * @param  string $cpf
     * @return array|null
     */
    public function fetchByCpf(string $cpf): ?array
    {
        $response = Http::withToken($this->token)
                        ->acceptJson()
                        ->post($this->baseUrl, [
                            'cpf' => $cpf,
                        ]);

        if (! $response->successful()) {
            return null;
        }

        $data = $response->json();

        return [
            'name'     => $data['name']     ?? null,
            'category' => $data['category']['name'] ?? null,
            'allowed'  => $data['allowed']  ?? false,
        ];
    }
}
