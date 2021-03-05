<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailConviteRevisor extends Mailable
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
    public function __construct($user, $evento, $subject, $informacoes = "")
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
        $subject = 'Easy - Convite de Evento';
        // return $this->markdown('emails.user.welcome')->with([
        //     'user' => $this->nomeUsuarioPai,
        //     'evento' => $this->nomeEvento,                   
        //     'funcao' => $this->nomeFuncao,                   
        //     'senha' => $this->senhaTemporaria,                   
        // ]);->subject($this->subject)
        // ->view('emails.emailConviteRevisor')
        // ->with([
        //     'user'      => $this->user,
        //     'info'      => $this->informacoes,
        //     'evento'    => $this->evento,
        // ]);
        return  $this->from('lmtsteste@gmail.com', 'Easy')
                    ->subject($this->subject)
                    ->markdown('emails.emailConviteRevisor', [
                        'user'      => $this->user,
                        'info'      => $this->informacoes,
                        'evento'    => $this->evento,
                    ]);
                    
    }
}
