<?php

namespace App\Filament\Resources\Sections\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class SectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('idSubDepartment')
                    ->label('Sub Departemen')
                    ->relationship(
                        name: 'subDepartment',
                        titleAttribute: 'subDepartmentName'
                    )
                    ->default(fn() => request()->input('subdepartment_id'))
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->department ? "{$record->department->businessUnit->regional->company->companyName} -> {$record->department->businessUnit->regional->regionalName} -> {$record->department->businessUnit->businessUnitName} -> {$record->department->departmentName} -> {$record->subDepartmentName}" : 'N/A')
                    ->preload()
                    ->searchable()
                    ->required(),
                TextInput::make('sectionName')
                    ->required()
                    ->maxLength(255),
                TextInput::make('code')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
