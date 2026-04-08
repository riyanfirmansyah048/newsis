<?php

namespace App\Mail;

use App\Models\BookingOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingOrderSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public BookingOrder $bookingOrder,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pengajuan Booking Order Baru',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-order-submitted',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
