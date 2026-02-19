<?php

namespace App\Filament\Resources\BppbInks\Pages;

use App\Filament\Resources\BppbInks\BppbInkResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditBppbInk extends EditRecord
{
    protected static string $resource = BppbInkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
