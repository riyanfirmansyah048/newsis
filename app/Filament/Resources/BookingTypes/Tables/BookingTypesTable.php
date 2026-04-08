<?php

namespace App\Filament\Resources\BookingTypes\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TrashedFilter;

class BookingTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Jenis Booking')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('notification_email')
                    ->label('Email Notifikasi')
                    ->searchable()
                    ->copyable()
                    ->placeholder('-'),
                TextColumn::make('notification_cc')
                    ->label('CC Email')
                    ->limit(40)
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('units_count')
                    ->label('Jumlah Unit')
                    ->counts('units'),
                TextColumn::make('orders_count')
                    ->label('Jumlah Booking Order')
                    ->counts('orders'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
