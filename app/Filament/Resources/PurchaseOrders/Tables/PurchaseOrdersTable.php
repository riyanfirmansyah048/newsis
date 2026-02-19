<?php

namespace App\Filament\Resources\PurchaseOrders\Tables;

use Filament\Tables\Table;
use App\Models\Purchase_order;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Tables\Enums\RecordActionsPosition;

class PurchaseOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->query(
                auth()->user()->hasRole('admin') // Periksa apakah user memiliki role "admin"
                    ? Purchase_order::query() // Jika admin, tampilkan semua data
                    : Purchase_order::query()->where('user_id', auth()->id()) // Jika bukan admin, hanya tampilkan miliknya sendiri
            )
            ->columns([
                TextColumn::make('noPo')
                    ->label('No. PO')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('vendor.vendorName')
                    ->label('Vendor')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('datePo')
                    ->label('Tanggal PO')
                    ->date('d F Y')
                    ->sortable()
                    ->searchable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()
                        ->visible(fn() => auth()->user()->hasRole('admin')),
                    DeleteAction::make()
                        ->visible(fn() => auth()->user()->hasRole('admin')),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
