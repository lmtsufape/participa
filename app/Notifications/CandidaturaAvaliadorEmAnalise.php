<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CandidaturaAvaliadorEmAnalise extends Notification
{
    use Queueable;

    private $evento;

    /**
     * Create a new notification instance.
     *
     * @param mixed 
     */
    public function __construct($evento)
    {
        $this->evento = $evento;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject("Candidatura de avaliador em análise")
                    ->greeting("Olá, {$notifiable->name}!")
                    ->line("Recebemos sua candidatura para ser avaliador(a) no evento {$this->evento->nome}.")
                    ->line('Sua solicitação está em análise e você será notificado(a) assim que houver uma decisão.')
                    ->line('Agradecemos pelo seu interesse!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
