<?php

namespace App\Filament\Resources\PurchaseOrders\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Bppbs\BppbResource;
use App\Filament\Resources\PurchaseOrders\PurchaseOrderResource;
use App\Models\Bppb_item;
use App\Models\Bppb_ink;
use App\Models\Bppb_software;

class CreatePurchaseOrder extends CreateRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function getRedirectUrl(): string
    {
        return BppbResource::getUrl('edit', ['record' => $this->record->bppb_id]);
    }

    protected function afterCreate(): void
    {
        // Ambil data repeater dari form
        $state = $this->form->getRawState();
        $poItems = $state['po_items'] ?? [];
        $poInks = $state['po_inks'] ?? [];
        $poSoftwares = $state['po_softwares'] ?? [];

        // Items
        collect($poItems)->each(function ($item) {
            Bppb_item::where('item_id', $item['item_id'])
                ->where('bppb_id', $this->record->bppb_id)
                ->whereNull('purchase_order_id')
                ->limit($item['qty'])
                ->get()
                ->each(function ($bppbItem) {
                    $bppbItem->purchase_order_id = $this->record->id;
                    $bppbItem->save();
                });
        });

        // Inks
        collect($poInks)->each(function ($ink) {
            Bppb_ink::where('ink_id', $ink['ink_id'])
                ->where('bppb_id', $this->record->bppb_id)
                ->whereNull('purchase_order_id')
                ->limit($ink['qty'])
                ->get()
                ->each(function ($bppbInk) {
                    $bppbInk->purchase_order_id = $this->record->id;
                    $bppbInk->save();
                });
        });

        // Softwares
        collect($poSoftwares)->each(function ($software) {
            Bppb_software::where('software_id', $software['software_id'])
                ->where('bppb_id', $this->record->bppb_id)
                ->whereNull('purchase_order_id')
                ->limit($software['qty'])
                ->get()
                ->each(function ($bppbSoftware) {
                    $bppbSoftware->purchase_order_id = $this->record->id;
                    $bppbSoftware->save();
                });
        });
    }
}
