<?php

namespace App\Filament\Resources\Users\RelationManagers;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class AssetsInksRelationManager extends RelationManager
{
    protected static string $relationship = 'assetsInks';

    protected static ?string $title = 'Inks';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ink.name')
                    ->label('Nama Ink')
                    ->sortable(),
                TextColumn::make('bpb.noBpb')
                    ->label('No BPB')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ]);
    }
}
