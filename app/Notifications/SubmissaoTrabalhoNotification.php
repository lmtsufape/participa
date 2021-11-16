<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubmissaoTrabalhoNotification extends Notification
{
    use Queueable;

    public $user;
    public $subject;
    public $trabalho;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $subject, $trabalho)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->trabalho = $trabalho;
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
                    ->from('lmtsteste@gmail.com', 'Easy ')
                    ->subject($this->subject)
                    ->greeting("Olá {$this->user->name}!")
                    ->line("O sistema Easy recebeu o seu trabalho intitulado '{$this->trabalho->titulo }' com sucesso!")
                    ->line('Acompanhe o calendário do seu evento para estar ciente das próximas etapas.')
                    // ->action('Redefinir Senha', route('password.reset', $this->token))
                    ->markdown('vendor.notifications.email');

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
