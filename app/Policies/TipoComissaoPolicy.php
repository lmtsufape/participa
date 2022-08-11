<?php

namespace App\Policies;

use App\Models\Submissao\TipoComissao;
use App\Models\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TipoComissaoPolicy
{
    use HandlesAuthorization;

    public function isCoordenadorDeOutraComissao(User $user, TipoComissao $comissao)
    {
        $idsCoordenadores = $comissao->membros()->wherePivot('isCoordenador', true)->get()->pluck('id')->all();

        return in_array($user->id, $idsCoordenadores);
    }
}
