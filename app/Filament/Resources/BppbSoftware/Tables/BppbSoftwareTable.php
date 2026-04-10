<?php

namespace App\Filament\Resources\BppbSoftware\Tables;

use App\Models\Bppb_software;
use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class BppbSoftwareTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->query(function () {
                $query = Bppb_software::query()
                    ->whereHas('bppb', function ($q) {
                        $q->where('bppb_type_id', 1);
                    });

                if (!auth()->user()?->hasRole('admin')) {
                    $query->where('pemohonIT', auth()->id());
                }

                return $query;
            })
            ->columns([
                TextColumn::make('software.name')
                    ->label('Nama Software')
                    ->searchable()
                    ->sortable(),
                // TextColumn::make('qty'),
                TextColumn::make('serialNumber')
                    ->label('Serial Number')
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                TextColumn::make('bppb.noBppb')
                    ->label('No BPPB IT')
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                TextColumn::make('noBppbPemohon')
                    ->label('No BPPB Pemohon')
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                TextColumn::make('Purchase_order.noPo')
                    ->label('No PO')
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Pemohon IT')
                    ->searchable(),
                TextColumn::make('userPemohon')
                    ->label('Pemohon')
                    ->searchable(),
            ])
            ->defaultSort('id', 'desc')
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
