<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\CheckboxList;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true),
                CheckboxList::make('permissions')
                    ->relationship('permissions', 'name')
                    ->label('Permissions')
                    ->columns(2) // Atur jumlah kolom biar rapi
                    ->searchable() // Bisa tetap cari di dalam daftar
            ]);
    }
}
