<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship(name: 'category', titleAttribute: 'name')
                    ->label('Kategori Barang')
                    ->default(fn() => request()->input('category_id'))
                    ->preload()
                    ->searchable()
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('name')
                    ->label('Nama Merek')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
