<?php

namespace App\Policies;

use App\Models\Submissao\Certificado;
use App\Models\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CertificadoPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function visualizarCertificado(User $user, Certificado $certificado)
    {
        return $certificado->usuarios->contains($user);
    }
}
