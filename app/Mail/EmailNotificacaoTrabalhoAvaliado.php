<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailNotificacaoTrabalhoAvaliado extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $nomeEvento;

    public $trabalho;

    public $revisor;

    public $autor;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $autor, string $nomeEvento, $trabalho, $revisor)
    {
        $this->user = $user;
        $this->autor = $autor;
        $this->nomeEvento = $nomeEvento;
        $this->trabalho = $trabalho;
        $this->revisor = $revisor;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(config('app.name').' - Trabalho/Atividade avaliada')
            ->markdown('emails.emailTrabalhoAvaliado')->with([
                        'user' => $this->user,
                        'autor' => $this->autor,
                        'evento' => $this->nomeEvento,
                        'trabalho' => $this->trabalho,
                        'revisor' => $this->revisor,
                    ]);
    }
}
