<?php

namespace App\Policies;

use App\User;
use App\Evento;
use App\ComissaoEvento;
use Illuminate\Support\Facades\Log;
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
      $comissao = ComissaoEvento::where([['eventosId', $evento->id], ['userId', $user->id]])->first();
      return $user->id === $evento->coordenador->id || !(is_null($comissao));
    }
}
