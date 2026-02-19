<?php

namespace App\Filament\Resources\BppbSoftware\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Bppbs\BppbResource;
use App\Filament\Resources\BppbSoftware\BppbSoftwareResource;

class CreateBppbSoftware extends CreateRecord
{
    protected static string $resource = BppbSoftwareResource::class;

    protected function getRedirectUrl(): string
    {
        return BppbResource::getUrl('edit', ['record' => $this->record->bppb_id]);
    }
}
