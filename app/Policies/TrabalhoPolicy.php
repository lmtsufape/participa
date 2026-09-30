<?php

namespace App\Policies;

use App\Models\Submissao\Trabalho;
use App\Models\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrabalhoPolicy
{
    use HandlesAuthorization;

    public function enviarVersaoFinal(User $user, Trabalho $trabalho): bool
    {
        return $this->isAutorTrabalho($user, $trabalho)
            && ! $trabalho->trashed() && $trabalho->status !== 'arquivado'
            && $trabalho->modalidade->estaEmPeriodoDeVersaoFinal()
            && ! $trabalho->versoesFinais()->exists();
    }

    public function visualizarVersaoFinal(User $user, Trabalho $trabalho): bool
    {
        return $this->isAutorTrabalho($user, $trabalho)
            || $trabalho->coautors()->where('autorId', $user->id)->exists()
            || $trabalho->atribuicoes()->where('user_id', $user->id)->exists()
            || (new EventoPolicy())->isCoordenadorOrComissaoCientifica($user, $trabalho->evento)
            || (new EventoPolicy())->isCoordenadorOrCoordenadorDasComissoes($user, $trabalho->evento)
            || \App\Models\Users\CoordEixoTematico::where('user_id', $user->id)
                ->where('evento_id', $trabalho->eventoId)->where('area_id', $trabalho->areaId)->exists();
    }

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
        if (!$trabalho->modalidade->correcaoHabilitada()) {
            return false;
        }
        $membro = $trabalho->evento->usuariosDaComissao()->where([['user_id', $user->id], ['evento_id', $trabalho->evento->id]])->first();
        $resultado = false;
        if ($user->id == $trabalho->evento->coordenadorId || ! (is_null($membro))) {
            $resultado = true;
        } elseif ($trabalho->autorId == $user->id && ($trabalho->modalidade->estaEmPeriodoDeCorrecao() || $trabalho->modalidade->estaEmPeriodoExtraDeCorrecao())) {
            $resultado = true;
        } else {
            $revisorAtribuido = $trabalho->atribuicoes->firstWhere('user_id', $user->id);
            if ($revisorAtribuido) {
                $resultado = true;
            }
        }

        return $resultado;
    }

    public function isCoordenadorOrComissaoOrAutor(User $user, Trabalho $trabalho)
    {
        $eventoPolicy = new EventoPolicy();

        return $this->isAutorTrabalho($user, $trabalho) || $eventoPolicy->isCoordenadorOrComissao($user, $trabalho->evento);
    }
}
