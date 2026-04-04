<?php

namespace App\Filament\Resources\Bpbs\Pages;

use App\Models\Bppb;
use App\Models\Purchase_order;
use App\Models\Assets_item;
use App\Models\Assets_ink;
use App\Models\Assets_software;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Bpbs\BpbResource;
use App\Filament\Resources\Bppbs\BppbResource;
use Illuminate\Validation\ValidationException;

class CreateBpb extends CreateRecord
{
    protected static string $resource = BpbResource::class;

    protected static bool $canCreateAnother = false; // Menonaktifkan tombol "Create & Create Another"

    public function mount(): void
    {
        parent::mount(); // pastikan memanggil parent

        $bppbId = request()->query('bppb_id');

        if ($bppbId) {
            session()->put('bppb_id', $bppbId); // Simpan ke session
        }
    }

    protected function getRedirectUrl(): string
    {
        $bppbId = session()->pull('bppb_id'); // Ambil dan langsung hapus dari session

        return $bppbId
            ? BppbResource::getUrl('edit', ['record' => $bppbId])
            : $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $poId = $data['po_id'] ?? request()->integer('po_id');

        if (! $poId) {
            Notification::make()
                ->title('Purchase Order belum dipilih')
                ->body('Pilih Purchase Order terlebih dahulu sebelum membuat BPB.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'po_id' => 'Pilih Purchase Order terlebih dahulu sebelum membuat BPB.',
            ]);
        }

        $purchaseOrder = Purchase_order::query()
            ->with(['bppb.user', 'bpb'])
            ->find($poId);

        if (! $purchaseOrder) {
            throw ValidationException::withMessages([
                'po_id' => 'Purchase Order tidak ditemukan.',
            ]);
        }

        if ($purchaseOrder->bpb()->exists()) {
            Notification::make()
                ->title('BPB sudah ada')
                ->body('Purchase Order tersebut sudah memiliki BPB.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'po_id' => 'Purchase Order tersebut sudah memiliki BPB.',
            ]);
        }

        $data['po_id'] = $purchaseOrder->id;
        $data['user_id'] = $purchaseOrder->bppb?->user_id;

        if ($purchaseOrder->bppb_id) {
            session()->put('bppb_id', $purchaseOrder->bppb_id);
        }

        return $data;
    }

    // protected function afterCreate(): void
    // {
    //     $bpb = $this->record; // BPB yang baru dibuat
    //     $bppbId = $bpb->bppb_id;
    //     $poId   = $bpb->po_id;

    //     DB::transaction(function () use ($bppbId, $poId, $bpb) {
    //         // Ambil BPPB
    //         $bppb = Bppb::with(['bppb_item', 'bppb_ink', 'bppb_software'])->findOrFail($bppbId);


    //         // ITEMS
    //         foreach ($bppb->bppb_item as $item) {
    //             Assets_item::create([
    //                 'user_id' => $bpb->user_id,
    //                 'item_id' => $item->item_id,
    //                 'bpb_id' => $bpb->id,
    //                 'bppb_item_id' => $item->id,
    //                 'idCompany' => $bpb->user?->company?->id,
    //                 'idRegional' => $bpb->user?->regional?->id,
    //                 'idDepartment' => $bpb->user?->department?->id,
    //                 'idPosition' => $bpb->user?->position?->id,
    //             ]);
    //         }

    //         // INKS
    //         foreach ($bppb->bppb_ink as $ink) {
    //             Assets_ink::create([
    //                 'user_id' => $bpb->user_id,
    //                 'ink_id' => $ink->ink_id,
    //                 'bpb_id' => $bpb->id,
    //                 'bppb_ink_id' => $ink->id,
    //                 'idCompany' => $bpb->user?->company?->id,
    //                 'idRegional' => $bpb->user?->regional?->id,
    //                 'idDepartment' => $bpb->user?->department?->id,
    //                 'idPosition' => $bpb->user?->position?->id,
    //             ]);
    //         }

    //         // SOFTWARE
    //         foreach ($bppb->bppb_software as $software) {
    //             Assets_software::create([
    //                 'user_id' => $bpb->user_id,
    //                 'software_id' => $software->software_id,
    //                 'bpb_id' => $bpb->id,
    //                 'bppb_software_id' => $software->id,
    //                 'idCompany' => $bpb->user?->company?->id,
    //                 'idRegional' => $bpb->user?->regional?->id,
    //                 'idDepartment' => $bpb->user?->department?->id,
    //                 'idPosition' => $bpb->user?->position?->id,
    //             ]);
    //         }
    //     });
    // }
}
