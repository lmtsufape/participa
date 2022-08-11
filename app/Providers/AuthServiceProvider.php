<?php

namespace App\Providers;

use App\Models\Submissao\Certificado;
use App\Models\Submissao\Evento;
use App\Models\Submissao\TipoComissao;
use App\Models\Submissao\Trabalho;
use App\Models\Users\Administrador;
use App\Policies\AdministradorPolicy;
use App\Policies\CertificadoPolicy;
use App\Policies\EventoPolicy;
use App\Policies\TipoComissaoPolicy;
use App\Policies\TrabalhoPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        Evento::class  =>  EventoPolicy::class,
        Administrador::class => AdministradorPolicy::class,
        Trabalho::class => TrabalhoPolicy::class,
        Certificado::class => CertificadoPolicy::class,
        TipoComissao::class => TipoComissaoPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
