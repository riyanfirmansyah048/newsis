<?php

namespace App\Mail;

use App\Models\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ServiceAssignedToPicMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Service $service,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Service ditugaskan kepada Anda (PIC)',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.service-assigned-to-pic',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
