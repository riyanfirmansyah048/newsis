<?php

namespace App\Filament\Resources\Internets\Schemas;

use App\Models\User;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Collection;
use Filament\Forms\Components\TextInput;

class InternetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('idUser')
                    ->options(
                        fn(): Collection => User::query()
                            ->get()
                            ->mapWithKeys(fn($user) => [$user->id => "{$user->NIK} - {$user->name}"])
                    )
                    ->label('Nama Karyawan')
                    ->placeholder('Pilih Karyawan')
                    ->preload()
                    ->searchable()
                    ->columnSpanFull()
                    ->default(auth()->id())
                    ->required()
                    ->reactive()
                    ->unique(ignoreRecord: true)
                    ->disabled(fn() => !auth()->user()->hasRole('admin')),
                Hidden::make('idUser')
                    ->default(fn() => auth()->id())
                    ->unique(ignoreRecord: true)
                    ->required(),
                Textarea::make('description')
                    ->label('Kebutuhan Internet')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('url')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('ip')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->default(request()->ip()),
                Toggle::make('activeStatus')
                    ->label('Is Active')
                    ->reactive()
                    ->visible(fn() => auth()->user()->hasRole('admin')),
            ]);
    }
}
