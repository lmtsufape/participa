<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Log;
use Closure;

class IsTemp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(Auth()->user()->usuarioTemp === true){
          return redirect('perfil');

        }
        return $next($request);
    }
}
