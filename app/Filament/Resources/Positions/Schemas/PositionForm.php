<?php

namespace App\Filament\Resources\Positions\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class PositionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('positionName')
                    ->label('Nama Jabatan')
                    ->required()
                    ->maxLength(255),
                TextInput::make('code')
                    ->label('Singkatan')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Keterangan')
                    ->required()
                    ->maxLength(1000),
            ]);
    }
}
