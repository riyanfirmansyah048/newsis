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

    protected function hasValidProfileEmail(): bool
    {
        $email = trim((string) auth()->user()?->email);

        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function mount(): void
    {
        parent::mount();

        if (! $this->hasValidProfileEmail()) {
            Notification::make()
                ->title('Email profile belum valid')
                ->body('Lengkapi email yang valid pada profile Anda terlebih dahulu sebelum membuat Booking Order.')
                ->danger()
                ->persistent()
                ->send();

            $this->redirect(BookingOrderResource::getUrl('index'));

            return;
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! $this->hasValidProfileEmail()) {
            Notification::make()
                ->title('Email profile belum valid')
                ->body('Lengkapi email yang valid pada profile Anda terlebih dahulu sebelum membuat Booking Order.')
                ->danger()
                ->persistent()
                ->send();

            throw ValidationException::withMessages([
                'user_id' => 'Lengkapi email yang valid pada profile Anda terlebih dahulu sebelum membuat Booking Order.',
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

        $data['user_id'] = auth()->id();

        if (! auth()->user()->hasRole('admin')) {
            $data['status'] = 'pending';
        }

        return $data;
    }
}
