<?php

namespace App\Filament\Resources\CategoryInks\Pages;

use App\Filament\Resources\CategoryInks\CategoryInkResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditCategoryInk extends EditRecord
{
    protected static string $resource = CategoryInkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
