<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailCodigoPreRegistro extends Mailable
{
    use Queueable, SerializesModels;

    public $preRegistro;

    public function __construct($preRegistro)
    {
        $this->preRegistro = $preRegistro;
    }

    public function build()
    {
        return $this->subject('(Participa - Plataforma de eventos) - Código de validação')
            ->view('emails.emailCodigoPreRegistro')
            ->with(['preRegistro' => $this->preRegistro], );
    }
}
