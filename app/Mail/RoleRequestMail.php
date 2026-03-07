<?php
// app/Mail/RoleRequestMail.php

namespace App\Mail;

use App\Models\RoleRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RoleRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public RoleRequest $request)
    {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[AirsoftPACA] Nouvelle demande Chef d'escouade : {$this->request->squad_name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.role-request',
        );
    }
}
