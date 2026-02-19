<?php

namespace App\Filament\Resources\BrandInks\Pages;

use App\Filament\Resources\BrandInks\BrandInkResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditBrandInk extends EditRecord
{
    protected static string $resource = BrandInkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
