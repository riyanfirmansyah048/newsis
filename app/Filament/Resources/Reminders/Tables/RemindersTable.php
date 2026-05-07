<?php

namespace App\Filament\Resources\Reminders\Tables;

use App\Models\Reminder;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class RemindersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->query(
                auth()->user()->hasRole('admin')
                    ? Reminder::query()->with('item', 'software', 'creator', 'reminderDates')
                    : Reminder::query()->with('item', 'software', 'creator', 'reminderDates')->where('created_by', auth()->id())
            )
            ->columns([
                TextColumn::make('target_name')
                    ->label('Nama Barang / Software')
                    ->searchable(query: function ($query, string $search) {
                        $query
                            ->whereHas('item', fn ($itemQuery) => $itemQuery->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('software', fn ($softwareQuery) => $softwareQuery->where('name', 'like', "%{$search}%"));
                    })
                    ->wrap(),
                TextColumn::make('target_type')
                    ->label('Jenis')
                    ->formatStateUsing(fn (string $state) => $state === 'software' ? 'Software' : 'Barang')
                    ->badge()
                    ->color(fn (string $state) => $state === 'software' ? 'info' : 'gray'),
                TextColumn::make('expire_date')
                    ->label('Tanggal Expired')
                    ->date('d F Y')
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email Tujuan')
                    ->searchable(),
                TextColumn::make('cc')
                    ->label('CC Email')
                    ->limit(50)
                    ->toggleable(),
                TextColumn::make('reminder_dates_count')
                    ->label('Total Reminder')
                    ->counts('reminderDates')
                    ->badge()
                    ->color('info'),
                TextColumn::make('delivery_status')
                    ->label('Status Pengiriman')
                    ->getStateUsing(fn(Reminder $record) => $record->reminderDates->where('is_sent', false)->isNotEmpty()
                        ? 'Belum Terkirim'
                        : 'Terkirim')
                    ->badge()
                    ->color(fn(string $state) => $state === 'Belum Terkirim' ? 'warning' : 'success'),
                TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Buat')
                    ->date('d F Y')
                    ->sortable(),
            ])
            ->defaultSort('expire_date', 'asc')
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()
                        ->visible(auth()->user()->can('update-reminder')),
                    DeleteAction::make()
                        ->visible(auth()->user()->can('delete-reminder')),
                ]),
            ]);
    }
}
