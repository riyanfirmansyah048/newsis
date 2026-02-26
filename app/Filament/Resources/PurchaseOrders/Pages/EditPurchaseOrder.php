<?php

namespace App\Filament\Resources\PurchaseOrders\Pages;

use App\Filament\Resources\PurchaseOrders\PurchaseOrderResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditPurchaseOrder extends EditRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->record;

        // =========================
        // BARANG
        // =========================
        $data['po_items'] = $record->bppb_items
            ->groupBy('item_id')
            ->map(fn($group) => [
                'item_id' => $group->first()->item_id,
                'qty' => $group->count(),
            ])
            ->values()
            ->toArray();
        // =========================
        // TINTA
        // =========================
        $data['po_inks'] = $record->bppb_inks
            ->groupBy('ink_id')
            ->map(fn($group) => [
                'ink_id' => $group->first()->ink_id,
                'qty' => $group->count(),
            ])
            ->values()
            ->toArray();

        // =========================
        // SOFTWARE
        // =========================
        $data['po_softwares'] = $record->bppb_softwares
            ->groupBy('software_id')
            ->map(fn($group) => [
                'software_id' => $group->first()->software_id,
                'qty' => $group->count(),
            ])
            ->values()
            ->toArray();

        return $data;
    }
}
