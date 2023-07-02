<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailLembreteUsuarioNaoCadastrado extends Mailable
{
    use Queueable, SerializesModels;

    public $nomeEvento;

    public $senhaTemporaria;

    public $email;

    public $coord;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $nomeEvento, string $senhaTemporaria, string $email, $coord)
    {
        $this->nomeEvento = $nomeEvento;
        $this->senhaTemporaria = $senhaTemporaria;
        $this->email = $email;
        $this->coord = $coord;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('lmtsteste@gmail.com', 'Participa ')
            ->subject('Sistema Participa - Lembrete de cadastro')
            ->markdown('emails.emailLembreteCadastro')->with([
                        'evento' => $this->nomeEvento,
                        'senha' => $this->senhaTemporaria,
                        'email' => $this->email,
                        'coord' => $this->coord,
                    ]);
    }
}
