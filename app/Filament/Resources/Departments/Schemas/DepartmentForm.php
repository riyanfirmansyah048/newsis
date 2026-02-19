<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('idBusinessUnit')
                    ->label('Bisnis Unit')
                    ->relationship(
                        name: 'businessunit',
                        titleAttribute: 'businessUnitName'
                    )
                    ->default(fn() => request()->input('business_unit_id'))
                    ->getOptionLabelFromRecordUsing(
                        fn($record) => ($record->regional?->company?->companyName ?? '-')
                            . ' → ' .
                            ($record->regional?->regionalName ?? '-')
                            . ' → ' .
                            $record->businessUnitName
                    )
                    ->preload()
                    ->searchable()
                    ->required(),
                TextInput::make('departmentName')
                    ->label('Nama Departemen')
                    ->required()
                    ->maxLength(255),
                TextInput::make('code')
                    ->label('Kode')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
