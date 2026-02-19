<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('companyName')
                    ->label('Nama Perusahaan')
                    ->required()
                    ->maxLength(255),
                TextInput::make('code')
                    ->label('Kode')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('image')
                    ->label('Images')
                    ->maxSize(1024)
                    ->image()
                    ->columnSpanFull()
                    ->directory('company-images'),
            ]);
    }
}
