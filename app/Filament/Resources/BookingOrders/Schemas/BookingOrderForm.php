<?php

namespace App\Filament\Resources\BookingOrders\Schemas;

use App\Models\BookingOrder;
use App\Models\BookingType;
use App\Support\BookingOrderAvailability;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class BookingOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Hidden::make('user_id')
                ->default(fn(?BookingOrder $record) => $record?->user_id ?? auth()->id())
                ->required(),

            Select::make('booking_type_id')
                ->label('Jenis Booking')
                ->options(fn() => BookingType::query()->active()->pluck('name', 'id')->toArray())
                ->searchable()
                ->preload()
                ->required()
                ->live()
                ->columnSpanFull()
                ->afterStateUpdated(fn(Set $set) => $set('assigned_unit_id', null)),

            TextInput::make('topic')
                ->label('Topik / Keterangan')
                ->required()
                ->columnSpanFull(),

            TextInput::make('host')
                ->label('Host')
                ->required(),

            DatePicker::make('date')
                ->label('Tanggal')
                ->required()
                ->live()
                ->afterStateUpdated(fn(Set $set) => $set('assigned_unit_id', null)),

            Select::make('start_time')
                ->label('Jam Mulai')
                ->options(fn() => BookingOrderAvailability::timeOptions())
                ->required()
                ->live()
                ->afterStateUpdated(fn(Set $set) => $set('end_time', null)),

            Select::make('end_time')
                ->label('Jam Selesai')
                ->options(fn(Get $get): array => BookingOrderAvailability::endTimeOptions($get('start_time')))
                ->required()
                ->live(),

            Select::make('status')
                ->label('Status')
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ])
                ->default('pending')
                ->required()
                ->live()
                ->columnSpanFull()
                ->visible(fn() => auth()->user()->can('update-booking-order')),

            TextInput::make('link')
                ->label('Link')
                ->url()
                ->columnSpanFull()
                ->visible(fn(Get $get) => auth()->user()->can('update-booking-order') && $get('status') === 'approved'),

            Textarea::make('rejection_reason')
                ->label('Alasan Reject')
                ->rows(3)
                ->columnSpanFull()
                ->visible(fn(Get $get) => auth()->user()->can('update-booking-order') && $get('status') === 'rejected'),

            Select::make('assigned_unit_id')
                ->label('Pilih Unit')
                ->options(fn(Get $get, ?BookingOrder $record): array => BookingOrderAvailability::availableUnitsOptions(
                    $get('booking_type_id'),
                    $get('date'),
                    $record?->id,
                    $get('assigned_unit_id'),
                ))
                ->searchable()
                ->preload()
                ->live()
                ->required()
                ->columnSpanFull()
                ->disabled(fn(Get $get, ?BookingOrder $record): bool => empty(BookingOrderAvailability::availableUnitsOptions(
                    $get('booking_type_id'),
                    $get('date'),
                    $record?->id,
                    $get('assigned_unit_id'),
                )))
                ->helperText('Pilih jenis booking dan tanggal terlebih dahulu, lalu pilih unit yang masih tersedia.'),

            Placeholder::make('availability_info')
                ->label('Ketersediaan Slot')
                ->columnSpanFull()
                ->content(fn(Get $get, ?BookingOrder $record) => BookingOrderAvailability::message(
                    $get('booking_type_id'),
                    $get('date'),
                    $record?->id,
                )),
        ]);
    }
}
