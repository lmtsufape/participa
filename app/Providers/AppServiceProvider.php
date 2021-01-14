<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('telefone', '\App\Utils\TelefoneValidation@validate', 'Celular inválido');
        Validator::extend('time', '\App\Utils\TimeValidation@validate', 'Hora inválida');
        // Validator::extend('after_time:attribute', '\App\Utils\AfterTimeValidation@validate', ':attribute deve ser em um horário depois do inicio.');
        \PagSeguro\Library::initialize();
        \PagSeguro\Library::cmsVersion()->setName("Easy")->setRelease("1.0.0");
        \PagSeguro\Library::moduleVersion()->setName("Easy")->setRelease("1.0.0");
    }
}
