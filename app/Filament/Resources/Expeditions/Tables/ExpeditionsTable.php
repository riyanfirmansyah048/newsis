<?php

namespace App\Filament\Resources\Expeditions\Tables;

use App\Models\Expedition;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Tables\Enums\RecordActionsPosition;

class ExpeditionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->columns([
                TextColumn::make('noExpedition')
                    ->label("No. Expedisi")
                    ->searchable()
                    ->sortable(),
                TextColumn::make('bppb.noBppb')
                    ->label("No. Bppb")
                    ->searchable()
                    ->sortable(),
                TextColumn::make('bppb.purchase_orders.noPo')
                    ->label("No. PO")
                    ->searchable()
                    ->sortable(),
                TextColumn::make('expeditor')
                    ->label("pengirim")
                    ->searchable(),
                TextColumn::make('bppb.user.name')
                    ->label("Penerima")
                    ->searchable(),
                TextColumn::make('dateStart')
                    ->label("Tgl Eks")
                    ->date('d F Y')
                    ->sortable(),
                TextColumn::make('datePrint')
                    ->label("Tgl Print")
                    ->date('d F Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                // EditAction::make(),
                Action::make('Print')
                    ->url(fn(Expedition $record) => route('expedition.print', $record->id))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-printer')
                    ->color('success'),
                DeleteAction::make(),
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
