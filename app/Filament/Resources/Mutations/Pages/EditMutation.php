<?php

namespace App\Filament\Resources\Mutations\Pages;

use App\Filament\Resources\Mutations\MutationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMutation extends EditRecord
{
    protected static string $resource = MutationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
