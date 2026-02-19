<?php

namespace App\Filament\Resources\BrandSoftware\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Models\Brand_software;
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
use App\Filament\Resources\Software\SoftwareResource;

class BrandSoftwareTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->columns([
                TextColumn::make('category_software.name')
                    ->label('Kategori Software')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Merek Software')
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
                    ->label('List Software')
                    ->url(
                        fn(Brand_software $brand_software) =>
                        SoftwareResource::getUrl('index', [
                            'brand_software_id' => $brand_software->id,
                            'category_software_id' => $brand_software->id,
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
