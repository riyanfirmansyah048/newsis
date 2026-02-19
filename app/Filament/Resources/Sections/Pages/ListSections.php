<?php

namespace App\Filament\Resources\Sections\Pages;

use App\Filament\Resources\Sections\SectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSections extends ListRecords
{
    protected static string $resource = SectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->url(
                    fn() =>
                    SectionResource::getUrl('create', [
                        'subdepartment_id' => request('subdepartment_id'),
                    ])
                ),
        ];
    }
}
