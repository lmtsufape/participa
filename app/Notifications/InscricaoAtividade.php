<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InscricaoAtividade extends Notification
{
    use Queueable;

    private $atividade;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($atividade)
    {
        $this->atividade = $atividade;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting("Olá, {$notifiable->name}!")
            ->line("Sua inscrição na atividade {$this->atividade->titulo} do evento {$this->atividade->evento->nome} foi realizada com sucesso!")
            ->subject('Confirmação de inscrição');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
