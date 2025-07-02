<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminActivityNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $link;
    protected $type; // 'proposal_submitted', 'proposal_approved', 'proposal_rejected', etc.

    /**
     * Create a new notification instance.
     *
     * @param string $message El mensaje de la notificación.
     * @param string|null $link Un enlace opcional al recurso relacionado.
     * @param string $type El tipo de notificación (ej. 'proposal_submitted').
     */
    public function __construct(string $message, ?string $link = null, string $type = 'general')
    {
        $this->message = $message;
        $this->link = $link;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->message,
            'link' => $this->link,
            'time' => now()->diffForHumans(), // Esto se usará en el JS
            'type' => $this->type,
        ];
    }
}
