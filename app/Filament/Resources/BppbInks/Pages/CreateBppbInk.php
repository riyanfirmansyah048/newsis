<?php

namespace App\Filament\Resources\BppbInks\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Bppbs\BppbResource;
use App\Filament\Resources\BppbInks\BppbInkResource;

class CreateBppbInk extends CreateRecord
{
    protected static string $resource = BppbInkResource::class;

    protected function getRedirectUrl(): string
    {
        return BppbResource::getUrl('edit', ['record' => $this->record->bppb_id]);
    }
}
