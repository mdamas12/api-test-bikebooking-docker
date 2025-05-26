<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CompanyApplication extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $company)
    {
        //
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



    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Solicitud de empresa recibida')
            ->greeting("Hola {$this->company->company_name},")
            ->line('Hemos recibido tu solicitud de registro.')
            ->line('Tu solicitud se encuentra en estado **pendiente** y será revisada por nuestro equipo.')
            ->line('Te informaremos por correo una vez que sea validada.')
            ->salutation('Saludos cordiales, el equipo de soporte');
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
