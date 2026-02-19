<?php

namespace App\Filament\Resources\Software\Pages;

use App\Filament\Resources\Software\SoftwareResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSoftware extends ListRecords
{
    protected static string $resource = SoftwareResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->url(
                    fn() =>
                    SoftwareResource::getUrl('create', [
                        'category_software_id' => request('category_software_id'),
                        'brand_software_id' => request('brand_software_id'),
                    ])
                ),
        ];
    }
}
