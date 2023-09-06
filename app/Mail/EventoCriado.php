<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventoCriado extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $subject;

    public $informacoes;

    public $evento;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $subject, $evento, $informacoes = '')
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->evento = $evento;
        $this->informacoes = $informacoes;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject($this->subject)
            ->markdown('emails.emailEventoCriado')
            ->with([
                'user' => $this->user,
                'info' => $this->informacoes,
                'evento' => $this->evento,
            ]);
    }
}
