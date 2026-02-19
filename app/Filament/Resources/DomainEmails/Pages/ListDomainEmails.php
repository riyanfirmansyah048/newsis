<?php

namespace App\Filament\Resources\DomainEmails\Pages;

use App\Filament\Resources\DomainEmails\DomainEmailResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDomainEmails extends ListRecords
{
    protected static string $resource = DomainEmailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
