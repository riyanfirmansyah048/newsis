<?php

namespace App\Filament\Resources\SubDepartments\Pages;

use App\Filament\Resources\SubDepartments\SubDepartmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSubDepartments extends ListRecords
{
    protected static string $resource = SubDepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->url(
                    fn() =>
                    SubDepartmentResource::getUrl('create', [
                        'department_id' => request('department_id'),
                    ])
                ),
        ];
    }
}
