<?php

namespace App\Filament\Resources\Regionals\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class RegionalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('idCompany')
                    ->relationship(name: 'company', titleAttribute: 'companyName')
                    ->label('Nama Perusahaan')
                    ->default(fn() => request()->input('company_id'))
                    ->preload()
                    ->searchable()
                    ->required(),
                TextInput::make('regionalName')
                    ->label('Nama Regional/Lokasi')
                    ->required()
                    ->maxLength(255),
                TextInput::make('code')
                    ->label('Kode')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
