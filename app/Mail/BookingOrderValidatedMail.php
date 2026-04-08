<?php

namespace App\Mail;

use App\Models\BookingOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingOrderValidatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public BookingOrder $bookingOrder,
        public string $validatorName,
    ) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->bookingOrder->status) {
            'approved' => 'Booking Order Disetujui',
            'rejected' => 'Booking Order Ditolak',
            default => 'Booking Order',
        };

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-order-validated',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
