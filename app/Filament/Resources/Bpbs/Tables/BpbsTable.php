<?php

namespace App\Filament\Resources\Bpbs\Tables;

use App\Models\Bpb;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Tables\Enums\RecordActionsPosition;

class BpbsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                auth()->user()->hasRole('admin') // Periksa apakah user memiliki role "admin"
                    ? Bpb::query() // Jika admin, tampilkan semua data
                    : Bpb::query()->where('user_id', auth()->id()) // Jika bukan admin, hanya tampilkan miliknya sendiri
            )
            // ->query(
            //     auth()->user()->hasRole('admin')
            //         ? Bpb::query()->whereHas('purchase_order.bppb', function ($q) {
            //             $q->where('bppb_type_id', [1, 2]);
            //         })
            //         : Bpb::query()
            //         ->where('user_id', auth()->id())
            //         ->whereHas('purchase_order.bppb', function ($q) {
            //             $q->where('bppb_type_id', 1);
            //         })
            // )
            ->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->columns([
                // TextColumn::make('noBpb')
                //     ->sortable()
                //     ->searchable(),
                TextColumn::make('noBpb')
                    ->label('No. BPB')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('purchase_order.bppb.noBppb')
                    ->label('No. BPPB')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('purchase_order.noPo')
                    ->label("No. PO")
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->sortable(),
                TextColumn::make('dateBpb')
                    ->dateTime()
                    ->date('d F Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('dateBpb', 'desc')
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn($record) => auth()->user()->hasRole('admin') && ! $record->trashed()),
                Action::make('print')
                    ->label('Print BPB')
                    ->icon('heroicon-m-printer')
                    ->color('info')
                    ->url(
                        fn($record) =>
                        route('bpb.print', ['id' => $record->id])
                    )
                    ->visible(fn($record) => ! $record->trashed()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    // ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
