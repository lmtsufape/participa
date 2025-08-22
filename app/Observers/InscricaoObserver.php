<?php

namespace App\Observers;

use App\Models\Inscricao\Inscricao;
use App\Models\Inscricao\InscricaoPCD;

class InscricaoObserver
{
    /**
     * Handle the Inscricao "deleting" event.
     *
     * @param  \App\Models\Inscricao\Inscricao  $inscricao
     * @return void
     */
    public function deleting(Inscricao $inscricao)
    {
        // Se uma inscrição for cancelada, removemos também a solicitação PCD associada,
        // permitindo que o usuário possa solicitar novamente se desejar.
        InscricaoPCD::where('user_id', $inscricao->user_id)
                    ->where('evento_id', $inscricao->evento_id)
                    ->delete();
    }
}
