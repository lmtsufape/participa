<?php

namespace App\Policies;

use App\Models\Submissao\Trabalho;
use App\Models\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrabalhoPolicy
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

    public function isAutorTrabalho(User $user, Trabalho $trabalho)
    {
        return $trabalho->autorId == $user->id;
    }

    public function permissaoVisualizarParecer(User $user, Trabalho $trabalho)
    {
        return $this->isAutorTrabalho($user, $trabalho) && $trabalho->status == 'avaliado';
    }
}
