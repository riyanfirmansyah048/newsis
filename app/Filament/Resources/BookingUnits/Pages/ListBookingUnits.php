<?php

namespace App\Filament\Resources\BookingUnits\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\BookingUnits\BookingUnitResource;

class ListBookingUnits extends ListRecords
{
    protected static string $resource = BookingUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
