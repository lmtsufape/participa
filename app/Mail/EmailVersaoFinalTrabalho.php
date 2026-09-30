<?php

namespace App\Mail;

use App\Models\Submissao\Trabalho;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVersaoFinalTrabalho extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Trabalho $trabalho, public string $nomeDestinatario)
    {
    }

    public function build()
    {
        return $this->subject(config('app.name').' - Versão final de trabalho enviada')
            ->markdown('emails.emailVersaoFinalTrabalho');
    }
}
