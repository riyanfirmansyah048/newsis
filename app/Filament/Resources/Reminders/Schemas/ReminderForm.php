<?php

namespace App\Filament\Resources\Reminders\Schemas;

use App\Models\Item;
use App\Models\Reminder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
                    ->options(fn() => Item::query()->orderBy('name')->pluck('name', 'id'))
                    ->required()
                    ->columnSpanFull()
                    ->getOptionLabelUsing(fn($value): ?string => Item::find($value)?->name),
                DatePicker::make('expire_date')
                    ->label('Tanggal Expired')
                    ->required(),
                TextInput::make('email')
                    ->label('Email Tujuan Reminder')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->default(fn(?Reminder $record) => $record?->email ?? Reminder::DEFAULT_TO_EMAIL),
                Textarea::make('cc')
                    ->label('CC Email')
                    ->rows(3)
                    ->placeholder("contoh:\nriyanfirmansyah@sanbe-farma.com, riyan_firmansyah048@gmail.com")
                    ->helperText('Pisahkan beberapa email dengan koma. Email Anda akan terisi otomatis sebagai default CC.')
                    ->default(fn(?Reminder $record) => $record?->cc ?? (auth()->user()?->email ?: ''))
                    ->columnSpanFull(),
                Repeater::make('reminderDates')
                    ->relationship('reminderDates')
                    ->label('Tanggal Reminder')
                    ->schema([
                        DatePicker::make('reminder_date')
                            ->label('Tanggal Pengingat')
                            ->maxDate(fn(Get $get) => $get('../../expire_date'))
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
