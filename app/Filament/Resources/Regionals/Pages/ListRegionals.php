<?php

namespace App\Filament\Resources\Regionals\Pages;

use App\Filament\Resources\Regionals\RegionalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRegionals extends ListRecords
{
    protected static string $resource = RegionalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->url(
                    fn() =>
                    RegionalResource::getUrl('create', [
                        'company_id' => request('company_id'),
                    ])
                ),
        ];
    }
}
