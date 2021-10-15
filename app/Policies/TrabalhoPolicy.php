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

    public function permissaoCorrecao(User $user, Trabalho $trabalho)
    {
        $membro = $trabalho->evento->usuariosDaComissao()->where([['user_id', $user->id], ['evento_id', $trabalho->evento->id]])->first();
        $resultado = false;
        if($user->id == $trabalho->evento->coordenadorId || !(is_null($membro)))
        {
            $resultado = true;
        }else if($trabalho->autorId == $user->id && ($trabalho->modalidade->inicioCorrecao <= now() && now() <= $trabalho->modalidade->fimCorrecao)){
            $resultado = true;
        }
        return $resultado;
    }

    public function isCoordenadorOrComissaoOrAutor(User $user, Trabalho $trabalho) 
    {
        $eventoPolicy = new EventoPolicy();
        return $this->isAutorTrabalho($user, $trabalho) || $eventoPolicy->isCoordenadorOrComissao($user, $trabalho->evento);
    }
}
