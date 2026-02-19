<?php

namespace App\Filament\Resources\DomainEmails\Pages;

use App\Filament\Resources\DomainEmails\DomainEmailResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDomainEmail extends CreateRecord
{
    protected static string $resource = DomainEmailResource::class;
}
