<?php

namespace App\Filament\Resources\DomainEmails\Pages;

use App\Filament\Resources\DomainEmails\DomainEmailResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditDomainEmail extends EditRecord
{
    protected static string $resource = DomainEmailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
