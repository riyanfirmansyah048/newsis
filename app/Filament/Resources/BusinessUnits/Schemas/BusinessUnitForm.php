<?php

namespace App\Filament\Resources\BusinessUnits\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class BusinessUnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('idRegional')
                    ->label('Nama Regional')
                    ->relationship(
                        name: 'regional',
                        titleAttribute: 'regionalName'
                    )
                    ->default(fn() => request()->input('regional_id'))
                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->company->companyName} -> {$record->regionalName}")
                    ->preload()
                    ->searchable()
                    ->required(),
                TextInput::make('businessUnitName')
                    ->label('Nama Bisnis Unit')
                    ->required()
                    ->maxLength(255),
                TextInput::make('code')
                    ->label('Kode')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
