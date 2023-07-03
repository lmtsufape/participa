<?php

namespace App\Notifications;

use App\Models\Submissao\Evento;
use App\Models\Users\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LembreteRevisorCompletarCadastro extends Notification
{
    use Queueable;

    public $evento;

    public $coord;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Evento $evento, User $coord)
    {
        $this->evento = $evento;
        $this->coord = $coord;
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
            ->subject('Lembrete para completar o cadastro')
            ->line("Este e-mail é um lembrete de que você foi indicado(a) pela coordenação do evento {$this->evento->nome} ({$this->coord->email}) para atuar como avaliador(a) ou parecerista de atividades e/ou trabalhos acadêmicos e que **necessita completar o seu cadastro para ter acesso aos trabalhos para avaliação**")
            ->line('Agradecemos de antemão pela sua disponibilidade para colaborar com a realização deste evento.');
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
