<?php

namespace App\Filament\Resources\BookingTypes\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\BookingTypes\BookingTypeResource;

class EditBookingType extends EditRecord
{
    protected static string $resource = BookingTypeResource::class;
}
