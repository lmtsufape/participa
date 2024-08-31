<?php

namespace App\Mail;

use App\Models\Submissao\Trabalho;
use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AvisoPeriodoCorrecao extends Mailable
{
    use Queueable, SerializesModels;

    public $fimCorrecao;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user,
        public Trabalho $trabalho,
    )
    {
        $this->fimCorrecao = Carbon::parse($trabalho->modalidade->fimCorrecao)->isoFormat('dddd, D \d\e MMMM, HH:mm');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Aviso Periodo Correcao',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.avisoPeriodoCorrecao',
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
}
