<?php

namespace App\Filament\Resources\BookingOrders\Pages;

use App\Mail\BookingOrderValidatedMail;
use App\Support\BookingOrderAvailability;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\BookingOrders\BookingOrderResource;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class EditBookingOrder extends EditRecord
{
    protected static string $resource = BookingOrderResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['end_time'] ?? null) <= ($data['start_time'] ?? null)) {
            throw ValidationException::withMessages([
                'end_time' => 'Jam selesai harus lebih besar dari jam mulai.',
            ]);
        }

        if (! BookingOrderAvailability::hasQuota($data['booking_type_id'], $data['date'], $this->record->id)) {
            Notification::make()
                ->title('Tanggal sudah penuh')
                ->body('Kuota booking pada tanggal tersebut sudah habis. Silakan pilih tanggal lain.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'date' => 'Kuota booking di tanggal tersebut sudah penuh.',
            ]);
        }

        if (blank($data['assigned_unit_id'] ?? null)) {
            Notification::make()
                ->title('Unit belum dipilih')
                ->body('Pilih salah satu unit yang tersedia untuk tanggal tersebut.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'assigned_unit_id' => 'Pilih salah satu unit yang tersedia untuk tanggal tersebut.',
            ]);
        }

        if (! BookingOrderAvailability::unitAvailable(
            $data['booking_type_id'],
            $data['date'],
            (int) $data['assigned_unit_id'],
            $this->record->id,
        )) {
            Notification::make()
                ->title('Unit tidak tersedia')
                ->body('Unit yang dipilih sudah tidak tersedia untuk tanggal tersebut. Silakan pilih unit lain.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'assigned_unit_id' => 'Unit yang dipilih sudah tidak tersedia untuk tanggal tersebut.',
            ]);
        }

        $status = $data['status'] ?? $this->record->status;

        if ($status === 'approved' && blank($data['link'] ?? null)) {
            Notification::make()
                ->title('Link wajib diisi')
                ->body('Isi link terlebih dahulu saat Booking Order di-approve.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'link' => 'Isi link terlebih dahulu saat Booking Order di-approve.',
            ]);
        }

        if ($status === 'rejected' && blank($data['rejection_reason'] ?? null)) {
            Notification::make()
                ->title('Alasan reject wajib diisi')
                ->body('Isi alasan reject terlebih dahulu saat Booking Order ditolak.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'rejection_reason' => 'Isi alasan reject terlebih dahulu saat Booking Order ditolak.',
            ]);
        }

        if ($status === 'rejected') {
            $data['assigned_unit_id'] = null;
            $data['link'] = null;
        }

        if ($status === 'approved') {
            $data['rejection_reason'] = null;
        }

        if (in_array($status, ['approved', 'rejected'])) {
            $data['validated_by'] = auth()->id();
            $data['validated_at'] = now();
        }

        return $data;
    }

    protected function afterSave(): void
    {
        if (! $this->record->wasChanged('status') || ! in_array($this->record->status, ['approved', 'rejected'])) {
            return;
        }

        $this->record->loadMissing(['user.department', 'bookingType', 'assignedUnit']);

        $recipients = collect([
            $this->record->user?->email,
            $this->record->bookingType?->notification_email,
        ])
            ->filter()
            ->unique()
            ->values()
            ->all();

        $ccRecipients = collect(explode(',', (string) $this->record->bookingType?->notification_cc))
            ->map(fn ($email) => trim($email))
            ->filter(fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values()
            ->all();

        if (empty($recipients) && empty($ccRecipients)) {
            return;
        }

        $validatorName = auth()->user()?->name ?? 'Admin';
        $validatorEmail = trim((string) auth()->user()?->email);
        $mail = new BookingOrderValidatedMail($this->record, $validatorName);

        if (filter_var($validatorEmail, FILTER_VALIDATE_EMAIL)) {
            $mail->from($validatorEmail, $validatorName);
        }

        Mail::to($recipients)
            ->cc($ccRecipients)
            ->send($mail);
    }
}
