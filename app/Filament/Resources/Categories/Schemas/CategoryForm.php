<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Kategori Software')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('codeAsset')
                    ->label('Kode Asset Software')
                    ->columnSpanFull(),
            ]);
    }
}
