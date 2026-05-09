<?php

namespace App\Filament\Resources\BookingOrders\Pages;

use Filament\Actions\Action;
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

        if (! BookingOrderResource::hasValidProfileContact()) {
            Notification::make()
                ->title('Profil belum lengkap')
                ->body('Lengkapi data Anda seperti Email, ext, dan Departemen terlebih dahulu agar proses Booking Order berjalan normal. Anda akan diarahkan ke halaman profile.')
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
            Action::make('calendar')
                ->label('Tampilan Kalender')
                ->icon('heroicon-o-calendar-days')
                ->color('success')
                ->url(BookingOrderResource::getUrl('calendar')),
        ];
    }
}
