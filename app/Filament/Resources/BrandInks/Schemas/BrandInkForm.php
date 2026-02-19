<?php

namespace App\Filament\Resources\BrandInks\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class BrandInkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_ink_id')
                    ->relationship(name: 'category_ink', titleAttribute: 'name')
                    ->label('Kategori Tinta')
                    ->default(fn() => request()->input('category_ink_id'))
                    ->preload()
                    ->searchable()
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('name')
                    ->label('Nama Merek Tinta')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
