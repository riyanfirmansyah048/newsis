<?php

namespace App\Filament\Resources\BppbItems\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Bppbs\BppbResource;
use App\Filament\Resources\BppbItems\BppbItemResource;

class CreateBppbItem extends CreateRecord
{
    protected static string $resource = BppbItemResource::class;

    protected function getRedirectUrl(): string
    {
        return BppbResource::getUrl('edit', ['record' => $this->record->bppb_id]);
    }
}
