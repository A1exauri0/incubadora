<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TokenCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $correo;
    public $rolName; // Este será el nombre legible
    public $actionUrl;
    public $actionText;

    /**
     * Create a new message instance.
     */
    public function __construct($token, $correo, $rolFromDb, $actionUrl, $actionText)
    {
        $this->token = $token;
        $this->correo = $correo;
        $this->actionUrl = $actionUrl;
        $this->actionText = $actionText;

        // Mapeo de nombres de rol de la base de datos a nombres legibles
        $readableRoleNames = [
            'admin' => 'Administrador',
            'alumno' => 'Alumno',
            'asesor' => 'Asesor',
            'mentor' => 'Mentor',
            'emprendedor' => 'Emprendedor',
            'inversionista' => 'Inversionista',
        ];

        // Asigna el nombre legible, si no se encuentra, usa el nombre original de la DB
        $this->rolName = $readableRoleNames[$rolFromDb] ?? $rolFromDb;
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
        return new Content(
            view: 'emails.token_created', // Apunta a tu plantilla HTML completa
            with: [
                'token' => $this->token,
                'correo' => $this->correo,
                'rolName' => $this->rolName, // Aquí ya se pasa el nombre legible
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
