<?php

namespace App\Filament\Resources\Users\RelationManagers;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class AssetsSoftwareRelationManager extends RelationManager
{
    protected static string $relationship = 'assetsSoftwares';

    protected static ?string $title = 'Software';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('software.name')
                    ->label('Nama Software')
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
