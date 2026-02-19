<?php

namespace App\Filament\Resources\CategoryInks\Pages;

use App\Filament\Resources\CategoryInks\CategoryInkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCategoryInks extends ListRecords
{
    protected static string $resource = CategoryInkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
