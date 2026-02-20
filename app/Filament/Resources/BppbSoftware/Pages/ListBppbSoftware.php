<?php

namespace App\Filament\Resources\BppbSoftware\Pages;

use App\Filament\Resources\BppbSoftware\BppbSoftwareResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBppbSoftware extends ListRecords
{
    protected static string $resource = BppbSoftwareResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
