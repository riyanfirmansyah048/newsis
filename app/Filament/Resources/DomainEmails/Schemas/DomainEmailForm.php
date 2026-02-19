<?php

namespace App\Filament\Resources\DomainEmails\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class DomainEmailForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('idCompany')
                    ->relationship(name: 'company', titleAttribute: 'companyName')
                    ->label('Nama Perusahaan')
                    ->default(fn() => request()->input('company'))
                    ->preload()
                    ->searchable()
                    ->required(),
                TextInput::make('domainName')
                    ->label('Nama Domain')
                    ->required()
                    ->maxLength(255),
                TextInput::make('titleName')
                    ->label('Judul Domain')
                    ->required()
                    ->maxLength(255),
                TextInput::make('imap')
                    ->required()
                    ->maxLength(255),
                TextInput::make('pop3')
                    ->required()
                    ->maxLength(255),
                TextInput::make('smtp')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
