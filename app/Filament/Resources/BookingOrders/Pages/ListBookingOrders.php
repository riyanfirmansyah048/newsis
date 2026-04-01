<?php

namespace App\Filament\Resources\BookingOrders\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\BookingOrders\BookingOrderResource;

class ListBookingOrders extends ListRecords
{
    protected static string $resource = BookingOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
