<?php

namespace App\Filament\Resources\Expeditions\Pages;

use App\Filament\Resources\Expeditions\ExpeditionResource;
use App\Models\Expedition;
use App\Models\ExpeditionDetail;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditExpedition extends EditRecord
{
    protected static string $resource = ExpeditionResource::class;

    protected array $selectedItems = [];

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Print')
                ->url(fn(Expedition $record) => route('expedition.print', $record->id))
                ->openUrlInNewTab()
                ->icon('heroicon-o-printer')
                ->color('success'),
        ];
    }

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
        unset($data['include_items']);

        return $data;
    }

    protected function afterSave(): void
    {
        $record = $this->record;

        $bppb = $record->bppb()->with('purchase_orders')->first();
        $poId = $bppb?->purchase_orders->first()?->id;

        ExpeditionDetail::where(
            'expedition_id',
            $record->id
        )->delete();

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
