<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BookingOrders\BookingOrderResource;
use App\Filament\Resources\Bppbs\BppbResource;
use App\Filament\Resources\Emails\EmailResource;
use App\Filament\Resources\Internets\InternetResource;
use App\Filament\Resources\Services\ServiceResource;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BppbFastAccessStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Tambah BPPB', '')
                ->url(BppbResource::getUrl('create'))
                ->icon('heroicon-o-shopping-cart')
                ->color('primary'),

            Stat::make('Tambah Service', '')
                ->url(ServiceResource::getUrl('index'))
                ->icon('heroicon-o-wrench'),

            Stat::make('Tambah Internet', '')
                ->url(InternetResource::getUrl('create'))
                ->icon('heroicon-o-globe-alt'),

            Stat::make('Pengajuan Email', '')
                ->url(EmailResource::getUrl('create'))
                ->icon('heroicon-o-envelope'),

            Stat::make('Booking Order', '')
                ->url(BookingOrderResource::getUrl('index'))
                ->icon('heroicon-o-calendar-days'),
        ];
    }

    protected function getHeading(): ?string
    {
        return 'Fast Access';
    }

    protected function getColumns(): int|array|null
    {
        return 5;
    }
}
