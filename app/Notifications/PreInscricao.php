<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Submissao\Evento;
use App\Models\Users\User;

class PreInscricao extends Notification
{
    use Queueable;

    private $evento;
    private $user;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\Submissao\Evento $evento
     * @param \App\Models\Users\User $user
     * @return void
     */
    public function __construct(Evento $evento, User $user)
    {
        $this->evento = $evento;
        $this->user = $user;
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
                    ->subject("Pré-inscrição realizada no evento {$this->evento->nome}")
                    ->view('emails.pre_inscricao', [
                        'evento' => $this->evento,
                        'user' => $this->user,
                    ]);
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
