<?php

namespace App\Filament\Resources\BppbInks\Pages;

use App\Filament\Resources\BppbInks\BppbInkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBppbInks extends ListRecords
{
    protected static string $resource = BppbInkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
