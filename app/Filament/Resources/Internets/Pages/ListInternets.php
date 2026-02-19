<?php

namespace App\Filament\Resources\Internets\Pages;

use App\Filament\Resources\Internets\InternetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInternets extends ListRecords
{
    protected static string $resource = InternetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
