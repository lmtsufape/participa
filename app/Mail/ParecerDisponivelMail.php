<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ParecerDisponivelMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $url;
    protected $trabalho;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($trabalho, $url)
    {
        $this->trabalho = $trabalho->load('evento', 'autor');
        $this->url = $url;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: config('app.name').' - Parecer disponível'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.parecer-disponivel',
            with: [
                'trabalho' => $this->trabalho,
                'url' => $this->url
            ],
        );
    }

}
