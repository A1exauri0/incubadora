<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AsesorActivityNotification extends Notification implements ShouldQueue
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
        // 1. Usa tu vista personalizada 'emails.nueva_propuesta'.
        // 2. Pasa los datos que la vista espera (projectName, projectClave, submitterName, proposalLink).
        //    Es importante que los nombres de las variables coincidan.
        return (new MailMessage)
            ->subject('Nueva Propuesta de Proyecto Pendiente de tu Revisión')
            ->view('emails.nueva_propuesta', [
                'projectName' => $this->projectName,
                'projectClave' => $this->projectKey, // Mapea la variable 'projectKey' a 'projectClave' para la vista.
                'submitterName' => $this->studentName, // Mapea 'studentName' a 'submitterName'.
                'proposalLink' => $this->reviewLink, // Mapea 'reviewLink' a 'proposalLink'.
            ]);
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