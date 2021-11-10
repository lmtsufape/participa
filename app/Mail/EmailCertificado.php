<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailCertificado extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $cargo;
    public $nomeEvento;
    public $pdf;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $cargo, String $nomeEvento, $pdf)
    {
      $this->user            = $user;
      $this->cargo           = $cargo;
      $this->nomeEvento      = $nomeEvento;
      $this->pdf             = $pdf;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      return  $this->from('lmtsteste@gmail.com', 'Easy ')
                    ->subject("Sistema Easy - Certificado")
                    ->markdown('emails.emailEnviarCertificado')->with([
                      'user'     => $this->user,
                      'cargo'    => $this->cargo,
                      'evento'   => $this->nomeEvento,
                  ])->attachData($this->pdf->output(), "Certificado.pdf");
    }
}
