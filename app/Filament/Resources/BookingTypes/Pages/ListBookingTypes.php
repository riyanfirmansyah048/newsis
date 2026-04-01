<?php

namespace App\Filament\Resources\BookingTypes\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\BookingTypes\BookingTypeResource;

class ListBookingTypes extends ListRecords
{
    protected static string $resource = BookingTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
