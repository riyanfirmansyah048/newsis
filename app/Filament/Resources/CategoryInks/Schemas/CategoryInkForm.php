<?php

namespace App\Filament\Resources\CategoryInks\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class CategoryInkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Kategori Tinta')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('codeAsset')
                    ->label('Kode Asset Tinta')
                    ->columnSpanFull(),
            ]);
    }
}
