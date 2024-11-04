<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockGetParams
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se a requisição é GET e se possui parâmetros
        if ($request->isMethod('get') && $request->query()) {
            // Retorna um erro 400 ou redireciona, se desejar
            abort(403, 'Parâmetros GET não permitidos para esta rota');
        }

        return $next($request);
    }
}
