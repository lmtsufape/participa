<?php

namespace App\Mail;

use App\Models\Submissao\Evento;
use App\Models\Users\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailInscritosPersonalizado extends Mailable
{
    use Queueable, SerializesModels;

    public Evento $evento;

    public User $user;

    public string $assuntoPersonalizado;

    public string $mensagemPersonalizada;

    /**
     * Create a new message instance.
     */
    public function __construct(Evento $evento, User $user, string $assunto, string $mensagem)
    {
        $this->evento = $evento;
        $this->user = $user;
        $this->assuntoPersonalizado = $assunto;
        $this->mensagemPersonalizada = $mensagem;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this
            ->subject($this->assuntoPersonalizado)
            ->markdown('emails.emailInscritosPersonalizado')
            ->with([
                'evento' => $this->evento,
                'user' => $this->user,
                'mensagemPersonalizada' => $this->mensagemPersonalizada,
            ]);
    }
}
