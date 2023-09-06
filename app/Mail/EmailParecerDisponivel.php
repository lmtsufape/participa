<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailParecerDisponivel extends Mailable
{
    use Queueable, SerializesModels;

    public $evento;

    public $trabalho;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($evento, $trabalho)
    {
        $this->evento = $evento;
        $this->trabalho = $trabalho;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(config('app.name').' - Parecer disponível')
            ->markdown('emails.emailParecerDisponivel')->with([
                        'evento' => $this->evento,
                        'senha' => $this->trabalho,
                    ]);
    }
}
