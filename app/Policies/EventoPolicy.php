<?php

namespace App\Policies;

use App\User;
use App\Evento;
use App\ComissaoEvento;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventoPolicy
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

    public function isCoordenador(User $user, Evento $evento){
      return $user->id === $evento->coordenador->id;
    }

    public function isPublishOrIsCoordenador(User $user, Evento $evento) {
      if ($user->id === $evento->coordenador->id || $evento->publicado) {
        return true;
      }
      return false;
    }

    public function isCoordenadorOrComissao(User $user, Evento $evento) {
      $membro = $evento->usuariosDaComissao()->where([['user_id', $user->id], ['evento_id', $evento->id]])->first();
      return $user->id === $evento->coordenador->id || !(is_null($membro));
    }

    public function isRevisorComAtribuicao(User $user) {
      if ($user->atribuicao != null && count($user->atribuicao) > 0) {
        return true;
      }
      return false;
    }
}
