<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailLembrete extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $subject;

    public $informacoes;

    public $trabalhos;

    public $dataLimite;

    public $evento;

    public $coord;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $subject, $informacoes, $trabalhos, $dataLimite, $evento, $coord)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->informacoes = $informacoes;
        $this->trabalhos = $trabalhos;
        $this->dataLimite = $dataLimite;
        $this->evento = $evento;
        $this->coord = $coord;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // $subject = 'Eventos - Lembrete de Evento';
        // return  $this->from('lmtsteste@gmail.com', 'Eventos - LMTS')
        //             ->subject($this->subject)
        //             ->view('emails.emailLembreteRevisor')
        //             ->with([
        //                 'user' => $this->user,
        //                 'info' => $this->informacoes,

        //             ]);
        return  $this->from('lmtsteste@gmail.com', 'Participa')
                    ->subject($this->subject)
                    ->markdown('emails.emailLembreteRevisor')->with([
                        'user' => $this->user,
                        'info' => $this->informacoes,
                        'trabalhos' => $this->trabalhos,
                        'dataLimite' => $this->dataLimite,
                        'evento' => $this->evento,
                        'coord' => $this->coord,
                    ]);
    }
}
