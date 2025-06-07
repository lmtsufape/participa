<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailConviteAvaliador extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $evento;
    public $informacoes;
    public $email;
    public $subject;


    /**
     * Create a new message instance.
     */
    public function __construct($user, $evento, $informacoes = '', $email = null)
    {
        $this->user         = $user;
        $this->evento       = $evento;
        $this->informacoes  = $informacoes;
        $this->email        = $email ?: $user->email;
        $this->subject      = config('app.name') . " - Convite para Avaliador";
    }

    public function build()
    {
        return $this
            ->to($this->email)
            ->subject($this->subject)
            ->markdown('emails.emailConviteAvaliador', [
                'user'        => $this->user,
                'evento'      => $this->evento,
                'informacoes' => $this->informacoes,
            ]);
    }
}
