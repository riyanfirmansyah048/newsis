<?php

namespace App\Filament\Widgets;

use App\Models\Assets_item;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class AssetsItemsWidget extends TableWidget
{
    protected static ?int $sort = 2;

    // Optional: judul widget
    protected static ?string $heading = 'Latest Assets Items';

    // Optional: jumlah record
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                auth()->user()->hasRole('admin')
                    ? Assets_item::query() // admin lihat semua
                    : Assets_item::query()->where('user_id', auth()->id()) // user biasa lihat miliknya
            )
            ->columns([
                TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),
                TextColumn::make('item.name')
                    ->label('Name')
                    ->sortable(),
                TextColumn::make('noAssetItem')
                    ->label('No Assets Item')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('User'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
