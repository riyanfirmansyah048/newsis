<?php

namespace App\Filament\Resources\BrandInks\Tables;

use App\Models\Brand_ink;
use Filament\Tables\Table;
use Filament\Actions\Action;
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
use App\Filament\Resources\Inks\InkResource;
use Filament\Tables\Enums\RecordActionsPosition;

class BrandInksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->columns([
                TextColumn::make('category_ink.name')
                    ->label('Kategori Tinta')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Merek Tinta')
                    ->sortable()
                    ->searchable(),
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
                Action::make('view')
                    ->label('List Tinta')
                    ->url(
                        fn(Brand_ink $brand_ink) =>
                        InkResource::getUrl('index', [
                            'brand_ink_id' => $brand_ink->id,
                            'category_ink_id' => $brand_ink->category_ink_id,
                        ])
                    ),
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
