<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailCertificadoSemAnexo extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $cargo;

    public $nomeEvento;

    public $link;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $cargo, string $nomeEvento, $link)
    {
        $this->user = $user;
        $this->cargo = $cargo;
        $this->nomeEvento = $nomeEvento;
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(config('app.name').' - Certificado')
            ->markdown('emails.emailEnviarCertificadoSemAnexo')
            ->with([
                'user' => $this->user,
                'cargo' => $this->cargo,
                'evento' => $this->nomeEvento,
                'link' => $this->link,
            ]);
    }
}
