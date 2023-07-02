<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('telefone', '\App\Utils\TelefoneValidation@validate', 'Celular inválido');
        Validator::extend('time', '\App\Utils\TimeValidation@validate', 'Hora inválida');
    }
}
