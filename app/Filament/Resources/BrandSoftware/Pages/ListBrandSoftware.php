<?php

namespace App\Filament\Resources\BrandSoftware\Pages;

use App\Filament\Resources\BrandSoftware\BrandSoftwareResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBrandSoftware extends ListRecords
{
    protected static string $resource = BrandSoftwareResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->url(
                fn() =>
                BrandSoftwareResource::getUrl('create', [
                    'category_software_id' => request('category_software_id'),
                ])
            ),
        ];
    }
}
