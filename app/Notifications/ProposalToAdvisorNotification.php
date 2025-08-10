<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProposalToAdvisorNotification extends Notification
{
    use Queueable;

    protected $projectName;
    protected $projectKey;
    protected $studentName;
    protected $reviewLink;

    /**
     * Create a new notification instance.
     */
    public function __construct($projectName, $projectKey, $studentName, $reviewLink)
    {
        $this->projectName = $projectName;
        $this->projectKey = $projectKey;
        $this->studentName = $studentName;
        $this->reviewLink = $reviewLink;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database']; // Notificar por correo y guardar en base de datos
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Nueva Propuesta de Proyecto Pendiente de tu Revisión')
                    ->line('Un alumno ha enviado una nueva propuesta de proyecto que requiere tu visto bueno.')
                    ->line('Nombre del Proyecto: ' . $this->projectName)
                    ->line('Clave del Proyecto: ' . $this->projectKey)
                    ->line('Enviado por: ' . $this->studentName)
                    ->action('Revisar Propuesta', url($this->reviewLink))
                    ->line('Gracias por usar nuestra aplicación!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Nueva propuesta de proyecto "' . $this->projectName . '" de ' . $this->studentName . ' pendiente de tu visto bueno.',
            'link' => $this->reviewLink,
            'type' => 'proposal_to_advisor',
            'project_key' => $this->projectKey,
            'project_name' => $this->projectName,
        ];
    }
}