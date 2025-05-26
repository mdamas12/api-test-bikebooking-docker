<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CompanyAtivated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $new_user){}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $frontendUrl = "http://localhost:9000"; //config('app.frontend_url'); // asegúrate de tener esto en tu .env

        $url = $frontendUrl . '/reset-password?token=' . $this->new_user['token'] . '&email=' . $this->new_user['user']['email'];

        return (new MailMessage)
            ->subject("Empresa Activada")
            ->greeting("Hola {$this->new_user['user']['name']}")
            ->line("Tu empresa ha sido activada para utilizar el sistema Bike Booking Engine.")
            ->line("tu usuario es: {$this->new_user['user']['email']} ")
            ->action("Ingresa al siguiente link para Activar tu usuario.", $url)   
            ->salutation("Saludos cordiales, el equipo de soporte");
    
    }

    // Datos que se guardan en la tabla notifications
    public function toDatabase(object $notifiable)
    {
        return [
            'company_id' => $this->new_user['company']['id'],
            'company_name' => $this->new_user['company']['company_name'],
            'message' => "La empresa {$this->new_user['company']['company_name']} ha sido activada correctamente.",
        ];
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
