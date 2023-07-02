<?php

namespace App\Mail\ConvidadoAtividade;

use App\Models\Users\Convidado;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailDesconvidandoAtividade extends Mailable
{
    use Queueable, SerializesModels;

    public $convidado;

    public $subject;

    public $informacoes;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Convidado $convidado, $subject, $informacoes = '')
    {
        $this->convidado = $convidado;
        $this->subject = $subject;
        $this->informacoes = $informacoes;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('lmtsteste@gmail.com', 'Eventos - LMTS')
            ->subject($this->subject)
            ->view('emails.convidadoAtividade.desconvidandoAtividade')
            ->with([
                'convidado' => $this->convidado,
                'info' => $this->informacoes,
            ]);
    }
}
