<?php

namespace App\Filament\Resources\BppbItems\Tables;

use App\Models\Bppb_item;
use Filament\Tables\Table;
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

class BppbItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                Bppb_item::query()
                    ->selectRaw('MIN(bppb_items.id) as id, bppb_items.item_id, bppb_items.bppb_id, SUM(qty) as qty')
                    ->addSelect([
                        'items.name as item_name',
                        'bppbs.noBppb as bppb_nobppb',
                        'purchase_orders.noPo as po_nopo',
                    ])
                    ->leftJoin('items', 'items.id', '=', 'bppb_items.item_id')
                    ->leftJoin('bppbs', 'bppbs.id', '=', 'bppb_items.bppb_id')
                    ->leftJoin('purchase_orders', 'purchase_orders.id', '=', 'bppb_items.purchase_order_id')
                    ->whereNull('bppb_items.deleted_at')
                    ->groupBy(
                        'bppb_items.item_id',
                        'bppb_items.bppb_id',
                        'items.name',
                        'bppbs.noBppb',
                        'purchase_orders.noPo'
                    )
                    ->orderBy('item_id') // orderBy kolom yang ada di groupBy
            )
            ->columns([
                TextColumn::make('item_name')
                    ->label('Nama Barang')
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
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
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
