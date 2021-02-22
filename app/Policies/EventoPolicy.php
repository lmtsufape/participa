<?php

namespace App\Policies;

use App\Models\Users\User;
use App\Models\Submissao\Evento;
use App\Models\Users\ComissaoEvento;
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
      return $user->id === $evento->coordenadorId;
    }

    public function isPublishOrIsCoordenador(User $user, Evento $evento) {
      
      if ($user->id === $evento->coordenadorId || $evento->publicado) {
        // print_r($evento);
        return true;
      }
      print_r($evento->coordenadorId);
      return false;
    }

    public function isCoordenadorOrComissao(User $user, Evento $evento) {
      $membro = $evento->usuariosDaComissao()->where([['user_id', $user->id], ['evento_id', $evento->id]])->first();
      return $user->id === $evento->coordenadorId || !(is_null($membro));
    }

    public function isRevisor(User $user, Evento $evento) {
      $autorizado = false;
      $revisoresDoEvento = $evento->revisors;
      foreach ($user->revisor as $revisor) {
        if ($revisoresDoEvento->contains($revisor)) {
          $autorizado = true;
        }
      }
      return $autorizado;
    }

    public function isRevisorComAtribuicao(User $user) {
      if ($user->revisor->trabalhosAtribuidos != null && count($user->revisor->trabalhosAtribuidos) > 0) {
        return true;
      }
      return false;
    }

    public function isCoordenadorOrComissaoOrganizadora(User $user, Evento $evento) {
      if ($evento->coordenadorId == $user->id || $user->id == $evento->coord_comissao_organizadora_id || $evento->usuariosDaComissaoOrganizadora()->where('user_id', $user->id)->first() != null) {
        return true;
      } 
      return false;
    }
}
