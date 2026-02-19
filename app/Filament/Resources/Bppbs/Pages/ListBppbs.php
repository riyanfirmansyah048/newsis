<?php

namespace App\Filament\Resources\Bppbs\Pages;

use App\Filament\Resources\Bppbs\BppbResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBppbs extends ListRecords
{
    protected static string $resource = BppbResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
