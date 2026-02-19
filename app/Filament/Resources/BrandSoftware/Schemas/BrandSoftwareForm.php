<?php

namespace App\Filament\Resources\BrandSoftware\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class BrandSoftwareForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_software_id')
                    ->relationship(name: 'category_software', titleAttribute: 'name')
                    ->label('Kategori Software')
                    ->default(fn() => request()->input('category_software_id'))
                    ->preload()
                    ->searchable()
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('name')
                    ->label('Nama Merek Software')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
