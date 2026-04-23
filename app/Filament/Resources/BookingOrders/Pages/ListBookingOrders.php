<?php

namespace App\Filament\Resources\BookingOrders\Pages;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\BookingOrders\BookingOrderResource;

class ListBookingOrders extends ListRecords
{
    protected static string $resource = BookingOrderResource::class;

    public function mount(): void
    {
        parent::mount();

        $email = trim((string) auth()->user()?->email);

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            Notification::make()
                ->title('Email profile belum valid')
                ->body('Lengkapi email pada profile Anda terlebih dahulu agar proses Booking Order berjalan normal. Anda akan diarahkan ke halaman profile.')
                ->warning()
                ->persistent()
                ->send();

            $this->redirectRoute('filament.sis.auth.profile');
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
