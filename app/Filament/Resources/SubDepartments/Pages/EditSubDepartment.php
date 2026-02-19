<?php

namespace App\Filament\Resources\SubDepartments\Pages;

use App\Filament\Resources\SubDepartments\SubDepartmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSubDepartment extends EditRecord
{
    protected static string $resource = SubDepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
