<?php

namespace App\Filament\Resources\Vendors\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class VendorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('vendorName')
                    ->label('Nama Vendor')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Textarea::make('vendorAddress')
                    ->label('Alamat Vendor')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('vendorDescription')
                    ->label('keterangan')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
