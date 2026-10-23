<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CartaDeAceiteMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $trabalho;
    protected $codigo;
    /**
     * Create a new message instance.
     */
    public function __construct($trabalho, $codigo)
    {
        $this->trabalho = $trabalho;
        $this->codigo = $codigo;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: config('app.name').' - Carta de Aceite',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $fotoEvento = $this->trabalho->evento->fotoEvento;

        $imgPath = null;

        if ($fotoEvento) {
            $relative = ltrim($fotoEvento, '/');

            if (Storage::disk('public')->exists($relative)) {
                $path = Storage::disk('public')->path($relative);

                $imgPath = is_file($path) && is_readable($path)
                    ? $path
                    : null;
            }
        }

        return new Content(
            view: 'emails.carta-de-aceite',
            with: [
                'trabalho' => $this->trabalho,
                'codigo' => $this->codigo,
                'imgPath' => $imgPath,
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
