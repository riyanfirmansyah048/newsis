<?php

namespace App\Filament\Resources\Inks\Pages;

use App\Filament\Resources\Inks\InkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInks extends ListRecords
{
    protected static string $resource = InkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->url(
                fn() =>
                InkResource::getUrl('create', [
                    'category_ink_id' => request('category_ink_id'),
                    'brand_ink_id' => request('brand_ink_id'),
                ])
            ),
        ];
    }
}
