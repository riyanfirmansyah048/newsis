<?php

namespace App\Filament\Resources\BppbSoftware\Tables;

use App\Models\Bppb_software;
use Dom\Text;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;

class BppbSoftwareTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(function () {
                $query = Bppb_software::query();

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
                TextColumn::make('qty'),
                TextColumn::make('user.name')
                    ->label('Pemohon IT')
                    ->searchable(),
                TextColumn::make('userPemohon')
                    ->label('Pemohon')
                    ->searchable(),
                TextColumn::make('bppb.noBppb')
                    ->label('No BPPB')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('Purchase_order.noPo')
                    ->label('No PO')
                    ->searchable()
                    ->sortable(),
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
