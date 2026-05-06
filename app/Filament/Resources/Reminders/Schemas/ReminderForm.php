<?php

namespace App\Filament\Resources\Reminders\Schemas;

use App\Models\Item;
use App\Models\Reminder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ReminderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('item_id')
                    ->label('Nama Barang / Lisensi')
                    ->searchable()
                    ->preload()
                    ->options(fn () => Item::query()->orderBy('name')->pluck('name', 'id'))
                    ->required()
                    ->getOptionLabelUsing(fn ($value): ?string => Item::find($value)?->name),
                DatePicker::make('expire_date')
                    ->label('Tanggal Expired')
                    ->required(),
                TextInput::make('email')
                    ->label('Email Tujuan Reminder')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->default(fn (?Reminder $record) => $record?->email ?? auth()->user()?->email),
                Repeater::make('reminderDates')
                    ->relationship('reminderDates')
                    ->label('Tanggal Reminder')
                    ->schema([
                        DatePicker::make('reminder_date')
                            ->label('Tanggal Pengingat')
                            ->maxDate(fn (Get $get) => $get('../../expire_date'))
                            ->required(),
                    ])
                    ->defaultItems(1)
                    ->columns(1)
                    ->columnSpanFull()
                    ->addActionLabel('Tambah Tanggal Reminder')
                    ->reorderable(false)
                    ->collapsible(),
            ]);
    }
}
