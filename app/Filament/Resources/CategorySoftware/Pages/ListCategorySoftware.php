<?php

namespace App\Filament\Resources\CategorySoftware\Pages;

use App\Filament\Resources\CategorySoftware\CategorySoftwareResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCategorySoftware extends ListRecords
{
    protected static string $resource = CategorySoftwareResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
