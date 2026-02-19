<?php

namespace App\Filament\Resources\Departments\Pages;

use App\Filament\Resources\Departments\DepartmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDepartments extends ListRecords
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->url(
                    fn() =>
                    DepartmentResource::getUrl('create', [
                        'business_unit_id' => request('business_unit_id'),
                    ])
                ),
        ];
    }
}
