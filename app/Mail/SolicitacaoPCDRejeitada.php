<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Users\User;
use App\Models\Submissao\Evento;

class SolicitacaoPCDRejeitada extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $evento;

    public function __construct(User $user, Evento $evento)
    {
        $this->user = $user;
        $this->evento = $evento;
    }

    public function build()
    {
        return $this->subject('Atualização sobre sua solicitação de inscrição PCD')
                    ->markdown('emails.pcd_rejeitada');
    }
}
