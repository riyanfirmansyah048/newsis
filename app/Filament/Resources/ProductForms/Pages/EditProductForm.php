<?php

namespace App\Filament\Resources\ProductForms\Pages;

use App\Filament\Resources\ProductForms\ProductFormResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditProductForm extends EditRecord
{
    protected static string $resource = ProductFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
