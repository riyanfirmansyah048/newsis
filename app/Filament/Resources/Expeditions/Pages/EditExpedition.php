<?php

namespace App\Filament\Resources\Expeditions\Pages;

use App\Filament\Resources\Expeditions\ExpeditionResource;
use App\Models\Expedition;
use App\Models\ExpeditionDetail;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditExpedition extends EditRecord
{
    protected static string $resource = ExpeditionResource::class;

    protected array $selectedItems = [];

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $details = ExpeditionDetail::where(
            'expedition_id',
            $this->record->id
        )->get();

        $data['include_items'] = $details
            ->map(
                fn($detail) =>
                $detail->type_id . '|' . $detail->product_form_id
            )
            ->toArray();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->selectedItems = $data['include_items'] ?? [];
        unset($data['include_items']); // 🔥 WAJIB supaya tidak masuk ke expeditions

        return $data;
    }

    protected function afterSave(): void
    {
        $record = $this->record;

        // ambil po_id dari bppb
        $bppb = $record->bppb()->with('purchase_orders')->first();
        $poId = $bppb?->purchase_orders->first()?->id;

        // hapus detail lama
        ExpeditionDetail::where(
            'expedition_id',
            $record->id
        )->delete();

        // insert ulang sesuai checkbox
        foreach ($this->selectedItems as $itemString) {
            [$typeId, $productFormId] = explode('|', $itemString);

            ExpeditionDetail::create([
                'expedition_id'   => $record->id,
                'po_id'           => $poId,
                'type_id'         => $typeId,
                'product_form_id' => $productFormId,
            ]);
        }
    }
}
