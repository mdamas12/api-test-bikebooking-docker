<?php


namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CompanyActivatedNotification extends Notification
{
    use Queueable;

    protected $company;

    public function __construct($company)
    {
        $this->company = $company;
    }

    /**
     * Define los canales de entrega
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Datos que se guardarán en la base de datos
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Empresa activada',
            'message' => "La empresa {$this->company->company_name} ha sido activada.",
            'company_id' => $this->company->id,
        ];
    }
}
