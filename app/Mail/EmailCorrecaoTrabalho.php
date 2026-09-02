<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailCorrecaoTrabalho extends Mailable
{
    use Queueable, SerializesModels;

    public $evento;
    public $trabalho;
    public $revisor;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($evento, $trabalho, $revisor)
    {
        $this->evento = $evento;
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
            ->subject(config('app.name').' - Correção de trabalho enviada')
            ->markdown('emails.emailCorrecaoTrabalho')->with([
                'evento' => $this->evento,
                'trabalho' => $this->trabalho,
                'revisor' => $this->revisor,
            ]);
    }
}
