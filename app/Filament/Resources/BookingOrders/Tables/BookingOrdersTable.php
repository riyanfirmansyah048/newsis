<?php

namespace App\Filament\Resources\BookingOrders\Tables;

use App\Models\BookingOrder;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class BookingOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                auth()->user()->hasRole('admin') // Periksa apakah user memiliki role "admin"
                    ? BookingOrder::query()->withTrashed() // Jika admin, tampilkan semua data termasuk yang di-cancel
                    : BookingOrder::query()->withTrashed()->where('user_id', auth()->id()) // Jika bukan admin, hanya tampilkan miliknya sendiri
            )
            ->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->columns([
                TextColumn::make('user.name')
                    ->label('Pemohon')
                    ->searchable(),
                TextColumn::make('bookingType.name')
                    ->label('Jenis Booking')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('topic')
                    ->label('Topik / Keterangan')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('host')
                    ->label('Host')
                    ->searchable(),
                TextColumn::make('date')
                    ->date('d F Y')
                    ->sortable(),
                TextColumn::make('start_time')
                    ->label('Mulai'),
                TextColumn::make('end_time')
                    ->label('Selesai'),
                TextColumn::make('assignedUnit.name')
                    ->label('Assigned Unit'),
            ])
            ->filters([
                SelectFilter::make('booking_type_id')
                    ->relationship('bookingType', 'name')
                    ->label('Jenis Booking'),
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(
                        fn($record) =>
                        !in_array($record->status, ['approved', 'rejected'])
                            || auth()->user()->hasRole('admin')
                    ),

                DeleteAction::make()
                    ->visible(
                        fn($record) =>
                        !in_array($record->status, ['approved', 'rejected'])
                            || auth()->user()->hasRole('admin')
                    ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('date', 'desc');
    }
}
