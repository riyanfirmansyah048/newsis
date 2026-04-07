<?php

namespace App\Filament\Resources\BookingTypes\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class BookingTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Nama Jenis Booking')
                ->required()
                ->unique(ignoreRecord: true),

            TextInput::make('notification_email')
                ->label('Email Notifikasi')
                ->email()
                ->placeholder('contoh: it-admin@sanbe-farma.com')
                ->helperText('Email PIC/departemen yang akan menerima notifikasi approval untuk jenis booking ini.'),

            Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),
        ]);
    }
}
