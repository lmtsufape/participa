<?php

namespace App\Policies;

use App\Models\Submissao\Evento;
use App\Models\Users\User;
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

    public function isCoordenador(User $user, Evento $evento)
    {
        return $user->id === $evento->coordenadorId || $evento->coordenadoresEvento()->where('email', $user->email)->exists() || $user->administradors()->exists();
    }

    public function isCriador(User $user, Evento $evento)
    {
        return $user->id === $evento->coordenadorId;
    }

    public function isCoordenadorDeOutrasComissoes(User $user, Evento $evento)
    {
        $idsCoordenadores = $evento->outrasComissoes->flatMap(function ($comissao) {
            return $comissao->membros()->wherePivot('isCoordenador', true)->get()->pluck('id');
        })->all();

        return in_array($user->id, $idsCoordenadores);
    }

    public function isPublishOrIsCoordenador(User $user, Evento $evento)
    {
        return $this->isCoordenador($user, $evento) || $evento->publicado;
    }

    public function isPublishOrIsCoordenadorOrCoordenadorDasComissoes(User $user, Evento $evento)
    {
        return $this->isPublishOrIsCoordenador($user, $evento) || $this->isCoordenadorOrCoordenadorDasComissoes($user, $evento);
    }

    public function isCoordenadorOrComissao(User $user, Evento $evento)
    {
        $membro = $evento->usuariosDaComissao()->where('user_id', $user->id)->first();

        return $this->isCoordenador($user, $evento) || ! (is_null($membro));
    }

    public function isRevisor(User $user, Evento $evento)
    {
        return $evento->revisors()->where('user_id', $user->id)->exists();
    }

    public function isRevisorComAtribuicao(User $user)
    {
        return $user->revisor->trabalhosAtribuidos != null && count($user->revisor->trabalhosAtribuidos) > 0;
    }

    public function isCoordenadorOrComissaoOrganizadora(User $user, Evento $evento)
    {
        return $this->isCoordenador($user, $evento) || $evento->usuariosDaComissaoOrganizadora()->where('user_id', $user->id)->first() != null;
    }

    public function isCoordenadorOrComissaoCientifica(User $user, Evento $evento)
    {
        return $this->isCoordenador($user, $evento) || $evento->usuariosDaComissao()->where('user_id', $user->id)->first() != null;
    }

    public function isCoordenadorOrCoordenadorDaComissaoOrganizadora(User $user, Evento $evento)
    {
        return $this->isCoordenador($user, $evento) || $this->isCoordenadorDaComissaoOrganizadora($user, $evento);
    }

    public function isCoordenadorDaComissaoOrganizadoraDoEventoPai(User $user, Evento $evento)
    {
        if ($evento->eventoPai) {
            return $this->isCoordenadorOrCoordenadorDaComissaoOrganizadora($user, $evento->eventoPai);
        }

        return false;
    }

    public function isCoordenadorDaComissaoOrganizadora(User $user, Evento $evento)
    {
        return $evento->userIsCoordComissaoOrganizadora($user);
    }

    public function isCoordenadorOrCoordenadorDaComissaoCientifica(User $user, Evento $evento)
    {
        return $this->isCoordenador($user, $evento) || $this->isCoordenadorDaComissaoCientifica($user, $evento);
    }

    public function isCoordenadorDaComissaoCientifica(User $user, Evento $evento)
    {
        return $evento->userIsCoordComissaoCientifica($user);
    }

    public function isCoordenadorOrCoordenadorDasComissoes(User $user, Evento $evento)
    {
        return $this->isCoordenador($user, $evento)
            || $this->isCoordenadorDaComissaoCientifica($user, $evento)
            || $this->isCoordenadorDaComissaoOrganizadora($user, $evento);
    }

    public function isCoordenadorOrCoordenadorDasComissoesOrIsCoordenadorDeOutrasComissoes(User $user, Evento $evento)
    {
        return $this->isCoordenadorDeOutrasComissoes($user, $evento)
            || $this->isCoordenadorOrCoordenadorDasComissoes($user, $evento);
    }

    public function isCoordenadorOrComissaoOrRevisorComAtribuicao(User $user, Evento $evento)
    {
        return $this->isCoordenadorOrComissao($user, $evento) || $this->isRevisorComAtribuicao($user);
    }
}
