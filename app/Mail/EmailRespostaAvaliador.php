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

    /**
     * Create a new message instance.
     */
    public function __construct($user, $evento, string $status, string $email = null)
    {
        $this->user    = $user;
        $this->evento  = $evento;
        $this->status  = $status;
        $this->email   = $email ?: $user->email;
        $this->subject = config('app.name') . ' - '
                       . ($status === 'aprovada'
                          ? 'Sua candidatura foi aprovada'
                          : 'Sua candidatura não foi aprovada');
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
            ]);
    }
}
