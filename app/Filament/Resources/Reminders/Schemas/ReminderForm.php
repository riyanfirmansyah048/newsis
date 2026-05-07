<?php

namespace App\Filament\Resources\Reminders\Schemas;

use App\Models\Item;
use App\Models\Reminder;
use App\Models\Software;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ReminderForm
{
    public static function configure(Schema $schema): Schema
    {
        $record = $schema->getRecord();
        $targetType = $record?->software_id ? 'software' : 'item';

        return $schema
            ->components([
                Select::make('target_type')
                    ->label('Jenis Reminder')
                    ->columnSpanFull()
                    ->options([
                        'item' => 'Barang',
                        'software' => 'Software / Lisensi',
                    ])
                    ->default($targetType)
                    ->native(false)
                    ->live()
                    ->dehydrated(false)
                    ->afterStateHydrated(fn (Set $set) => $set('target_type', $targetType))
                    ->afterStateUpdated(function (Set $set, ?string $state): void {
                        if ($state === 'software') {
                            $set('item_id', null);
                        } else {
                            $set('software_id', null);
                        }
                    })
                    ->required(),
                Select::make('item_id')
                    ->label('Nama Barang')
                    ->searchable()
                    ->live()
                    ->visible(fn (Get $get) => $get('target_type') !== 'software')
                    ->options(fn () => Item::query()->orderBy('name')->pluck('name', 'id'))
                    ->getOptionLabelUsing(fn ($value): ?string => Item::find($value)?->name)
                    ->required(fn (Get $get) => $get('target_type') !== 'software')
                    ->columnSpanFull(),
                Select::make('software_id')
                    ->label('Nama Software / Lisensi')
                    ->searchable()
                    ->live()
                    ->visible(fn (Get $get) => $get('target_type') === 'software')
                    ->options(fn () => Software::query()->orderBy('name')->pluck('name', 'id'))
                    ->getOptionLabelUsing(fn ($value): ?string => Software::find($value)?->name)
                    ->required(fn (Get $get) => $get('target_type') === 'software')
                    ->columnSpanFull(),
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
