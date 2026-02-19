<?php

namespace App\Filament\Resources\SubDepartments\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class SubDepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('idDepartment')
                    ->label('Departemen')
                    ->relationship(
                        name: 'department',
                        titleAttribute: 'departmentName'
                    )
                    ->default(fn() => request()->input('department_id'))
                    ->getOptionLabelFromRecordUsing(
                        fn($record) => ($record->businessUnit?->regional?->company?->companyName ?? '-')
                            . ' → ' .
                            ($record->businessUnit?->regional?->regionalName ?? '-')
                            . ' → ' .
                            ($record->businessUnit?->businessUnitName ?? '-')
                            . ' → ' .
                            ($record->departmentName ?? '-')
                    )
                    ->preload()
                    ->searchable()
                    ->required(),
                TextInput::make('subDepartmentName')
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
