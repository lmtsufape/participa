<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiInscricaoAba
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-KEY');
        $ip = $request->ip();
        $rota = $request->path();
        $cpf = $request->route('cpf');

        if($apiKey !== config('app.api_key')) {
            Log::warning('Acesso negado (API key inválida)', [
                'ip'    => $ip,
                'rota'  => $rota,
                'cpf'   => $cpf,
                'data'  => now()->toDateTimeString(),
            ]);

            return response()->json(['message' => 'API Key inválida'], 401);

        }
        Log::info('Acesso autorizado à API', [
            'ip' => $ip,
            'rota' => $rota,
            'data' => now()->toDateTimeString(),
        ]);

        return $next($request);
    }
}
