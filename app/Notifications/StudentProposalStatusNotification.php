<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentProposalStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $projectName;
    protected $newStatus;
    protected $rejectionReason;
    protected $proposalLink;

    public function __construct($projectName, $newStatus, $proposalLink, $rejectionReason = null)
    {
        $this->projectName = $projectName;
        $this->newStatus = $newStatus;
        $this->proposalLink = $proposalLink;
        $this->rejectionReason = $rejectionReason;
    }

    public function via($notifiable)
    {
        // La notificación se sigue enviando por correo electrónico y a la base de datos.
        return ['mail', 'database'];
    }

    /**
     * Obtiene la representación de correo electrónico de la notificación.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // En lugar de usar los métodos line() y action(), 
        // usaremos el método view() para cargar nuestra plantilla Blade personalizada.
        return (new MailMessage)
            ->subject('Actualización de tu Propuesta de Proyecto: "' . $this->projectName . '"')
            ->view('emails.estado_propuesta_alumno', [ 
                'projectName' => $this->projectName,
                'newStatus' => $this->newStatus,
                'rejectionReason' => $this->rejectionReason,
                'proposalLink' => $this->proposalLink,
                'userName' => $notifiable->name,
            ]);
    }

    public function toArray($notifiable)
    {
        $message = 'Tu propuesta "' . $this->projectName . '" ha sido ' . $this->newStatus . '.';
        
        if ($this->rejectionReason) {
            $message .= ' Motivo: ' . $this->rejectionReason;
        }

        return [
            'message' => $message,
            'link' => $this->proposalLink,
            'type' => 'proposal_' . strtolower($this->newStatus),
        ];
    }
}