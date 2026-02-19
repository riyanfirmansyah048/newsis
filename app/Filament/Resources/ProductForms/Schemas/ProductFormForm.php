<?php

namespace App\Filament\Resources\ProductForms\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class ProductFormForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Bentuk Barang')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Keterangan')
                    ->columnSpanFull()
                    ->nullable()
                    ->maxLength(255),
            ]);
    }
}
