<?php

namespace App\Filament\Resources\Bpbs\Pages;

use App\Models\Bppb;
use App\Models\Assets_item;
use App\Models\Assets_ink;
use App\Models\Assets_software;
use Illuminate\Support\Facades\DB;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Bpbs\BpbResource;
use App\Filament\Resources\Bppbs\BppbResource;

class CreateBpb extends CreateRecord
{
    protected static string $resource = BpbResource::class;

    protected static bool $canCreateAnother = false; // Menonaktifkan tombol "Create & Create Another"

    public function mount(): void
    {
        parent::mount(); // pastikan memanggil parent

        $bppbId = request()->query('bppb_id');
        $poId = request()->get('po_id');

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
