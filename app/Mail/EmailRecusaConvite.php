<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailRecusaConvite extends Mailable
{
    use Queueable, SerializesModels;

    public $coordenador;
    public $trabalho;
    public $justificativa;
    public $evento;
    public $revisor;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($coordenador, $trabalho, $justificativa, $evento, $revisor)
    {
        $this->coordenador = $coordenador;
        $this->trabalho = $trabalho;
        $this->justificativa = $justificativa;
        $this->evento = $evento;
        $this->revisor = $revisor;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Convite para avaliação recusado',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = config('app.name').' - Convite para avaliação recusado';

        return $this
            ->subject($this->subject)
            ->markdown('emails.emailRecusaConvite', [
                'coordenador' => $this->coordenador,
                'trabalho' => $this->trabalho,
                'justificativa' => $this->justificativa,
                'evento' => $this->evento,
                'revisor' => $this->revisor,
            ]);
    }
}
