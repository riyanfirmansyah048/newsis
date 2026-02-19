<?php

namespace App\Filament\Resources\Mutations\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;

class MutationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->columns([
                TextColumn::make('userFrom.name')
                    ->label('User Asal')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('userTo.name')
                    ->label('User Penerima')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Keterangan')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()
                        ->visible(fn($record) => auth()->user()->hasRole('admin') || in_array($record->status_id, [1, 2, 3])),
                    DeleteAction::make()
                        ->visible(fn($record) => auth()->user()->hasRole('admin') || in_array($record->status_id, [1, 2, 3])),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
