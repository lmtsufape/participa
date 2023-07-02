<?php

namespace App\Http\Middleware;

use Closure;

class IsTemp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth()->user()->usuarioTemp === true || Auth()->user()->endereco == null) {
            return redirect('perfil');
        }

        return $next($request);
    }
}
