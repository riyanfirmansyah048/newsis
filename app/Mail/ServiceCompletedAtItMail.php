<?php

namespace App\Mail;

use App\Models\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ServiceCompletedAtItMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Service $service,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Service selesai — barang siap di IT',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.service-completed-at-it',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
