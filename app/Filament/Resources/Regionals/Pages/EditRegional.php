<?php

namespace App\Filament\Resources\Regionals\Pages;

use App\Filament\Resources\Regionals\RegionalResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRegional extends EditRecord
{
    protected static string $resource = RegionalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
