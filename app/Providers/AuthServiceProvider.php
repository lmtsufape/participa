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
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Evento::class => EventoPolicy::class,
        Administrador::class => AdministradorPolicy::class,
        Trabalho::class => TrabalhoPolicy::class,
        Certificado::class => CertificadoPolicy::class,
        TipoComissao::class => TipoComissaoPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Gate para cadastro de usuários
        Gate::define('cadastrarUsuario', function ($user) {
            // Administradores sempre podem
            if (isset($user->administradors)) {
                return true;
            }

            // Coordenadores de eventos podem (eventos criados pelo usuário)
            if ($user->eventos()->exists()) {
                return true;
            }

            // Coordenadores de eventos atribuídos podem
            if ($user->eventosCoordenador()->exists()) {
                return true;
            }

            // Coordenadores da comissão científica podem
            if ($user->coordComissaoCientifica()->exists()) {
                return true;
            }

            // Coordenadores da comissão organizadora podem
            if ($user->coordComissaoOrganizadora()->exists()) {
                return true;
            }

            return false;
        });
    }
}
