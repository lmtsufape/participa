<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CorrecaoTrabalhoLiberadaMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $url;
    protected $trabalho;

    /**
     * Create a new message instance.
     */
    public function __construct($trabalho, $url)
    {
        $this->trabalho = $trabalho->load('evento', 'autor');
        $this->url = $url;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: config('app.name').' - Trabalho liberado para correção',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.correcao-trabalho-liberada',
            with: [
                'trabalho' => $this->trabalho,
                'url' => $this->url
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
