<?php

namespace App\Filament\Resources\Items\Tables;

use App\Filament\Resources\Items\ItemResource;
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
use Filament\Tables\Enums\RecordActionsPosition;

class ItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->columns([
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                TextColumn::make('brand.name')
                    ->label('Merek')
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nama Barang')
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Keterangan')
                    ->copyable()
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->url(fn($record) => ItemResource::getUrl('view', ['record' => $record])),
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
