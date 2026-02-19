<?php

namespace App\Filament\Resources\BppbItems\Pages;

use App\Filament\Resources\BppbItems\BppbItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBppbItems extends ListRecords
{
    protected static string $resource = BppbItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
