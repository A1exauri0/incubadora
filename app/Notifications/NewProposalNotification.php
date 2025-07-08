<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewProposalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $projectName;
    protected $projectClave;
    protected $submitterName;
    protected $proposalLink;

    /**
     * Crea una nueva instancia de notificación.
     *
     * @param string $projectName El nombre del proyecto.
     * @param string $projectClave La clave del proyecto.
     * @param string $submitterName El nombre del usuario que envió la propuesta.
     * @param string $proposalLink El enlace a la vista de propuestas.
     * @return void
     */
    public function __construct($projectName, $projectClave, $submitterName, $proposalLink)
    {
        $this->projectName = $projectName;
        $this->projectClave = $projectClave;
        $this->submitterName = $submitterName;
        $this->proposalLink = $proposalLink;
    }

    /**
     * Obtiene los canales de entrega de la notificación.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // Esta notificación se enviará por correo electrónico y se guardará en la base de datos
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
        return (new MailMessage)
                    ->subject('Nueva Propuesta de Proyecto Pendiente - IncubaTec ITTG') // Asunto del correo
                    // Usar la vista HTML personalizada y pasar los datos
                    ->view('emails.nueva_propuesta', [
                        'projectName' => $this->projectName,
                        'projectClave' => $this->projectClave,
                        'submitterName' => $this->submitterName,
                        'proposalLink' => $this->proposalLink,
                    ]);
    }

    /**
     * Obtiene la representación de la notificación para la base de datos.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'Nueva propuesta de proyecto de ' . $this->submitterName . ': "' . $this->projectName . '"',
            'link' => $this->proposalLink,
            'type' => 'proposal_submitted',
        ];
    }
}
