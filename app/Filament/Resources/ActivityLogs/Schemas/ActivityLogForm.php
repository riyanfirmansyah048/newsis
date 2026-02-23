<?php

namespace App\Filament\Resources\ActivityLogs\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ActivityLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('log_name')->disabled(),
                TextInput::make('description')->disabled(),

                TextInput::make('causer_name')
                    ->label('User')
                    ->disabled()
                    ->placeholder(fn($record) => $record?->causer?->name),

                TextInput::make('subject_id')
                    ->label('Record ID')
                    ->disabled(),

                TextInput::make('subject_type')
                    ->label('Model')
                    ->disabled()
                    ->columnSpanFull(),

                Textarea::make('properties')
                    ->label('Changes / Properties')
                    ->rows(12)
                    ->disabled()
                    ->formatStateUsing(
                        fn($state) =>
                        json_encode($state, JSON_PRETTY_PRINT)
                    )
                    ->columnSpanFull(),
            ]);
    }
}
