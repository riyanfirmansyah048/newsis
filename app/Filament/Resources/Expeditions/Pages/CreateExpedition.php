<?php

namespace App\Filament\Resources\Expeditions\Pages;

use App\Models\ExpeditionDetail;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Expeditions\ExpeditionResource;

class CreateExpedition extends CreateRecord
{
    protected static string $resource = ExpeditionResource::class;

    protected array $selectedItems = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->selectedItems = $data['include_items'] ?? [];
        unset($data['include_items']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->getRecord();

        $bppb = $record->bppb()->with('purchase_orders')->first();
        $poId = $bppb?->purchase_orders->first()?->id;

        foreach ($this->selectedItems as $itemString) {
            [$id, $productFormId] = explode('|', $itemString);

            ExpeditionDetail::create([
                'expedition_id'   => $record->id,
                'type_id'         => $id,
                'product_form_id' => $productFormId,
                'po_id'           => $poId,
            ]);
        }
    }
}
