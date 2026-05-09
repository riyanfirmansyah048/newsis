<?php

namespace App\Filament\Resources\BookingOrders\Pages;

use App\Models\BookingType;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use App\Filament\Resources\BookingOrders\BookingOrderResource;

class CalendarView extends Page
{
    protected static string $resource = BookingOrderResource::class;

    protected string $view = 'filament.pages.booking-calendar';

    public function getHeading(): string
    {
        return 'Kalender Booking';
    }

    public function getSubheading(): ?string
    {
        return 'Lihat semua booking dalam tampilan kalender.';
    }

    public function getBookingTypes()
    {
        return BookingType::query()->active()->pluck('name', 'id');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('list')
                ->label('Tampilan List')
                ->icon('heroicon-o-list-bullet')
                ->color('gray')
                ->url(BookingOrderResource::getUrl('index')),
            Action::make('create')
                ->label('Buat Booking')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->url(BookingOrderResource::getUrl('create')),
        ];
    }
}
