<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailLembretePrazoAvaliacao extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $subject;
    public $trabalhos;
    public $dataLimite;
    public $evento;
    public $coord;
    public $diasRestantes;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $subject, $trabalhos, $dataLimite, $evento, $coord, $diasRestantes)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->trabalhos = $trabalhos;
        $this->dataLimite = $dataLimite;
        $this->evento = $evento;
        $this->coord = $coord;
        $this->diasRestantes = $diasRestantes;
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
            ->markdown('emails.emailLembretePrazoAvaliacao')
            ->with([
                'user' => $this->user,
                'trabalhos' => $this->trabalhos,
                'dataLimite' => $this->dataLimite,
                'evento' => $this->evento,
                'coord' => $this->coord,
                'diasRestantes' => $this->diasRestantes,
            ]);
    }
}
