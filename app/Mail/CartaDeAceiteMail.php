<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CartaDeAceiteMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $trabalho;
    /**
     * Create a new message instance.
     */
    public function __construct($trabalho)
    {
        $this->trabalho = $trabalho;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Carta De Aceite',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $relative = ltrim($this->trabalho->evento->fotoEvento ?? '', '/');
        $imgPath = storage_path('app/public/' . $relative);

        return new Content(
            view: 'emails.carta-de-aceite',
            with: [
                'trabalho' => $this->trabalho,
                'imgPath' =>  file_exists($imgPath) ? $imgPath : null,
            ],
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
