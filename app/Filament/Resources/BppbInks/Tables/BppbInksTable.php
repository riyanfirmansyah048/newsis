<?php

namespace App\Filament\Resources\BppbInks\Tables;

use App\Models\Bppb_ink;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;

class BppbInksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                Bppb_ink::query()
                    ->selectRaw('MIN(bppb_inks.id) as id, bppb_inks.ink_id, bppb_inks.bppb_id, SUM(qty) as qty')
                    ->addSelect([
                        'inks.name as ink_name',
                        'bppbs.noBppb as bppb_nobppb',
                        'purchase_orders.noPo as po_nopo',
                    ])
                    ->leftJoin('inks', 'inks.id', '=', 'bppb_inks.ink_id')
                    ->leftJoin('bppbs', 'bppbs.id', '=', 'bppb_inks.bppb_id')
                    ->leftJoin('purchase_orders', 'purchase_orders.id', '=', 'bppb_inks.purchase_order_id')
                    ->whereNull('bppb_inks.deleted_at')
                    ->groupBy(
                        'bppb_inks.ink_id',
                        'bppb_inks.bppb_id',
                        'inks.name',
                        'bppbs.noBppb',
                        'purchase_orders.noPo'
                    )
                    ->orderBy('ink_id') // orderBy kolom yang ada di groupBy
            )
            ->columns([
                TextColumn::make('ink_name')
                    ->label('Nama Tinta')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('qty'),
                TextColumn::make('bppb_nobppb')
                    ->label('No BPPB')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('po_nopo')
                    ->label('No PO')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
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
