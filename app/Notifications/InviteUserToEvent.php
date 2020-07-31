<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InviteUserToEvent extends Notification implements ShouldQueue
{
    use Queueable;
    protected $user;
    protected $event;
    protected $guest;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($event, $user, $guest)
    {
        $this->user = $user;
        $this->event = $event;
        $this->guest = $guest;
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
     * @param  mixed  $event
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = route('confirm-invite', ['id' => $this->event->id]);
        return (new MailMessage)
            ->subject('Convite para Evento '. $this->event->name)
            ->greeting('Olá '. $this->user->name)
            ->line('Aqui está seu convite para participar do evento: '. $this->event->name)
            ->action('Clique aqui para confirmar', $url. '?code='. $this->guest->uuid_invite)
            ->line('Até mais.');
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
