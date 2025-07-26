<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailRespostaAvaliador extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $evento;
    public $status;   // string: "aprovada" ou "rejeitada"
    public $email;
    public $subject;
    public $eixo;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $evento, string $status, string $eixo, string $email = null)
    {
        $this->user    = $user;
        $this->evento  = $evento;
        $this->status  = $status;
        $this->eixo    = $eixo;
        $this->email   = $email ?: $user->email;
        $this->subject = config('app.name') . ' - '
                       . ($status === 'aprovada'
                          ? 'Candidatura homologada no ' . $eixo
                          : 'Candidatura não homologada no ' . $eixo);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->to($this->email)
            ->subject($this->subject)
            ->markdown('emails.emailRespostaAvaliador', [
                'user'   => $this->user,
                'evento' => $this->evento,
                'status' => $this->status,
                'eixo'   => $this->eixo,
            ]);
    }
}
