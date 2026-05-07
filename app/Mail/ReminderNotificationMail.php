<?php

namespace App\Mail;

use App\Models\Reminder;
use App\Models\ReminderDate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReminderNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Reminder $reminder,
        public ReminderDate $reminderDate,
        public string $bppbUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reminder Expired Item - ' . $this->reminder->target_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reminder-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
