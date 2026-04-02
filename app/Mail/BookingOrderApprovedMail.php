<?php

namespace App\Mail;

use App\Models\BookingOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingOrderApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public BookingOrder $bookingOrder,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Order',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-order-approved',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
