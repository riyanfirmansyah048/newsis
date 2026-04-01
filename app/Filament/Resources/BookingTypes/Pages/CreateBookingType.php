<?php

namespace App\Filament\Resources\BookingTypes\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\BookingTypes\BookingTypeResource;

class CreateBookingType extends CreateRecord
{
    protected static string $resource = BookingTypeResource::class;
}
