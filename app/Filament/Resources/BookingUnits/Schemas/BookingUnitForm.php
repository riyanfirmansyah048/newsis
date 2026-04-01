<?php

namespace App\Filament\Resources\BookingUnits\Schemas;

use App\Models\BookingType;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class BookingUnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('booking_type_id')
                ->label('Jenis Booking')
                ->options(fn () => BookingType::query()->active()->pluck('name', 'id')->toArray())
                ->searchable()
                ->preload()
                ->required(),

            TextInput::make('name')
                ->label('Nama Unit')
                ->required(),

            TextInput::make('identifier')
                ->label('Identifier')
                ->nullable()
                ->unique(ignoreRecord: true),

            Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),
        ]);
    }
}
