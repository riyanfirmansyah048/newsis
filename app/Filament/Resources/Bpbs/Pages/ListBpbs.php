<?php

namespace App\Filament\Resources\Bpbs\Pages;

use App\Filament\Resources\Bpbs\BpbResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBpbs extends ListRecords
{
    protected static string $resource = BpbResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
