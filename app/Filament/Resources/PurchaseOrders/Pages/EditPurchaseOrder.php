<?php

namespace App\Filament\Resources\PurchaseOrders\Pages;

use App\Filament\Resources\PurchaseOrders\PurchaseOrderResource;
use App\Models\Bppb_ink;
use App\Models\Bppb_item;
use App\Models\Bppb_software;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditPurchaseOrder extends EditRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    protected array $poItems = [];
    protected array $poInks = [];
    protected array $poSoftwares = [];

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


        $data['purchase_order_id'] = $record->id;
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->poItems = $data['po_items'] ?? [];
        $this->poInks = $data['po_inks'] ?? [];
        $this->poSoftwares = $data['po_softwares'] ?? [];

        // Hapus dari data utama supaya tidak error
        unset($data['po_items'], $data['po_inks'], $data['po_softwares']);

        return $data;
    }

    protected function afterSave(): void
    {
        $record = $this->record;
        $bppbId = $record->bppb_id;

        // =====================
        // 1️⃣ Lepaskan semua item dari PO ini
        // =====================
        Bppb_item::where('purchase_order_id', $record->id)
            ->update(['purchase_order_id' => null]);

        Bppb_ink::where('purchase_order_id', $record->id)
            ->update(['purchase_order_id' => null]);

        Bppb_software::where('purchase_order_id', $record->id)
            ->update(['purchase_order_id' => null]);


        // =====================
        // 2️⃣ Assign ulang BARANG
        // =====================
        foreach ($this->poItems as $item) {

            $rows = Bppb_item::where('bppb_id', $bppbId)
                ->where('item_id', $item['item_id'])
                ->whereNull('purchase_order_id')
                ->limit($item['qty'])
                ->get();

            foreach ($rows as $row) {
                $row->update(['purchase_order_id' => $record->id]);
            }
        }


        foreach ($this->poInks as $ink) {

            $rows = Bppb_ink::where('bppb_id', $bppbId)
                ->where('ink_id', $ink['ink_id'])
                ->whereNull('purchase_order_id')
                ->limit($ink['qty'])
                ->get();

            foreach ($rows as $row) {
                $row->update(['purchase_order_id' => $record->id]);
            }
        }

        foreach ($this->poSoftwares as $software) {

            $rows = Bppb_software::where('bppb_id', $bppbId)
                ->where('software_id', $software['software_id'])
                ->whereNull('purchase_order_id')
                ->limit($software['qty'])
                ->get();

            foreach ($rows as $row) {
                $row->update(['purchase_order_id' => $record->id]);
            }
        }
    }

    protected function afterDelete(): void
    {
        $record = $this->record;

        Bppb_item::where('purchase_order_id', $record->id)
            ->update(['purchase_order_id' => null]);

        Bppb_ink::where('purchase_order_id', $record->id)
            ->update(['purchase_order_id' => null]);

        Bppb_software::where('purchase_order_id', $record->id)
            ->update(['purchase_order_id' => null]);
    }
}
