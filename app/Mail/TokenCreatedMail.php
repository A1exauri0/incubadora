<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content; // Importa la clase Content
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// Asegúrate de que esta línea NO esté presente:
// use Illuminate\Mail\Mailables\Markdown;

class TokenCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $correo;
    public $rolName;
    public $actionUrl;
    public $actionText;

    /**
     * Create a new message instance.
     */
    public function __construct($token, $correo, $rolName, $actionUrl, $actionText)
    {
        $this->token = $token;
        $this->correo = $correo;
        $this->rolName = $rolName;
        $this->actionUrl = $actionUrl;
        $this->actionText = $actionText;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu Token de Registro para IncubaTec ITTG',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Usa Content para renderizar una vista Blade HTML normal
        return new Content(
            view: 'emails.token_created', // Apunta a tu plantilla HTML completa
            with: [
                'token' => $this->token,
                'correo' => $this->correo,
                'rolName' => $this->rolName,
                'actionUrl' => $this->actionUrl,
                'actionText' => $this->actionText,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
