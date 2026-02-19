<?php

namespace App\Filament\Resources\BrandInks\Pages;

use App\Filament\Resources\BrandInks\BrandInkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBrandInks extends ListRecords
{
    protected static string $resource = BrandInkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->url(
                fn() =>
                BrandInkResource::getUrl('create', [
                    'category_ink_id' => request('category_ink_id'),
                ])
            ),
        ];
    }
}
