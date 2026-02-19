<?php

namespace App\Filament\Resources\BppbItems\Pages;

use App\Filament\Resources\BppbItems\BppbItemResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditBppbItem extends EditRecord
{
    protected static string $resource = BppbItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
