<?php

namespace App\Filament\Resources\BookingOrders\Pages;

use App\Support\BookingOrderAvailability;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\BookingOrders\BookingOrderResource;
use Illuminate\Validation\ValidationException;

class CreateBookingOrder extends CreateRecord
{
    protected static string $resource = BookingOrderResource::class;

    public function mount(): void
    {
        parent::mount();

        if (blank(auth()->user()?->email)) {
            Notification::make()
                ->title('Email profile belum diisi')
                ->body('Lengkapi email pada profile Anda terlebih dahulu sebelum membuat Booking Order.')
                ->danger()
                ->persistent()
                ->send();

            $this->redirect(BookingOrderResource::getUrl('index'));

            return;
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (blank(auth()->user()?->email)) {
            Notification::make()
                ->title('Email profile belum diisi')
                ->body('Lengkapi email pada profile Anda terlebih dahulu sebelum membuat Booking Order.')
                ->danger()
                ->persistent()
                ->send();

            throw ValidationException::withMessages([
                'user_id' => 'Lengkapi email pada profile Anda terlebih dahulu sebelum membuat Booking Order.',
            ]);
        }

        if (($data['end_time'] ?? null) <= ($data['start_time'] ?? null)) {
            throw ValidationException::withMessages([
                'end_time' => 'Jam selesai harus lebih besar dari jam mulai.',
            ]);
        }

        if (! BookingOrderAvailability::hasQuota($data['booking_type_id'], $data['date'])) {
            Notification::make()
                ->title('Tanggal sudah penuh')
                ->body('Kuota booking pada tanggal tersebut sudah habis. Silakan pilih tanggal lain.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'date' => 'Kuota booking di tanggal tersebut sudah penuh.',
            ]);
        }

        $data['user_id'] = auth()->id();

        if (! auth()->user()->hasRole('admin')) {
            $data['status'] = 'pending';
        }

        if (($data['status'] ?? 'pending') === 'approved') {
            $assigned = BookingOrderAvailability::findAvailableUnitId(
                $data['booking_type_id'],
                $data['date'],
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

        return $data;
    }
}
