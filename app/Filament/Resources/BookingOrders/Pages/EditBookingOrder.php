<?php

namespace App\Filament\Resources\BookingOrders\Pages;

use App\Mail\BookingOrderApprovedMail;
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

        if (($data['status'] ?? $this->record->status) === 'rejected') {
            $data['assigned_unit_id'] = null;
        }

        return $data;
    }

    protected function afterSave(): void
    {
        if (! $this->record->wasChanged('status') || $this->record->status !== 'approved') {
            return;
        }

        $this->record->loadMissing(['user.department', 'bookingType', 'assignedUnit']);
        // disini
        $recipients = collect([
            $this->record->user?->email,
            env('BOOKING_IT_EMAIL', 'it-admin@sanbe-farma.com'),
        ])
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($recipients)) {
            return;
        }

        Mail::to($recipients)->send(new BookingOrderApprovedMail($this->record));
    }
}
