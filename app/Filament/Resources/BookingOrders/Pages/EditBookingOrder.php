<?php

namespace App\Filament\Resources\BookingOrders\Pages;

use App\Support\BookingOrderAvailability;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\BookingOrders\BookingOrderResource;
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

        if (($data['status'] ?? $this->record->status) === 'approved') {
            $assigned = BookingOrderAvailability::findAvailableUnitId(
                $data['booking_type_id'],
                $data['date'],
                $this->record->id,
            );

            if (! $assigned) {
                Notification::make()
                    ->title('Unit tidak tersedia')
                    ->body('Tidak ada unit yang tersedia untuk tanggal tersebut.')
                    ->danger()
                    ->send();

                throw ValidationException::withMessages([
                    'status' => 'Tidak ada unit tersedia untuk tanggal tersebut.',
                ]);
            }

            $data['assigned_unit_id'] = $assigned;
        }

        if (($data['status'] ?? $this->record->status) === 'rejected') {
            $data['assigned_unit_id'] = null;
        }

        return $data;
    }
}
