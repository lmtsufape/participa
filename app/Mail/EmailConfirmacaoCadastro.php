<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Users\User;

class EmailConfirmacaoCadastro extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->email = $user->email;
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
                ->subject('Cadastro Concluído - Participa')
                ->markdown('emails.confirmacao-cadastro',[
                    'user' => $this->user,
                ]);
    }
} 