<?php

namespace App\Filament\Resources\Bppbs\Pages;

use Carbon\Carbon;
use App\Models\Bpb;
use App\Models\Bppb;
use App\Models\Bppb_ink;
use App\Models\Bppb_item;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use App\Models\Bppb_software;
use App\Models\Purchase_order;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\Page;
use Filament\Actions\RestoreAction;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\ForceDeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Bpbs\BpbResource;
use App\Filament\Resources\Bppbs\BppbResource;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Auth\Access\AuthorizationException;
use App\Filament\Resources\BppbInks\BppbInkResource;
use App\Filament\Resources\BppbItems\BppbItemResource;
use App\Filament\Resources\BppbSoftware\BppbSoftwareResource;
use App\Filament\Resources\PurchaseOrders\PurchaseOrderResource;
use App\Livewire\BppbDetailWidget;

class EditBppbCustom extends Page implements HasForms
{
    use InteractsWithForms;
    protected static string $resource = BppbResource::class;

    protected string $view = 'filament.resources.bppbs.pages.edit-bppb-custom';

    public Bppb $record;
    public ?array $data = [];

    public ?int $category_id = null;
    public ?int $brand_id = null;
    public ?int $item_id = null;

    protected static ?string $title = 'Edit BPPB';

    public function mount(Bppb $record): void
    {
        if (!Auth::user()->can('update-bppb')) {
            throw new AuthorizationException('You do not have permission to update BPPB.');
        }

        $this->record = $record;

        //isi data awal dari record
        $this->data = [
            'description' => $this->record->description,
        ];
    }
    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Textarea::make('description')
                    ->label('Keterangan BPPB')
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    protected function getViewData(): array
    {
        $record = $this->record;

        return [
            'bppb_id' => $record->id ?? '',
            'noBppb' => $record->noBppb ?? '',
            'name' => $record->user?->name ?? '',
            'NIK' => $record->user?->NIK ?? '',
            'created_at' => $record->created_at ?? '',
            'company' => $record->user?->company?->companyName ?? '',
            'regional' => $record->user?->regional?->regionalName ?? '',
            'businessunit' => $record->user?->businessunit?->businessUnitName ?? '',
            'department' => $record->user?->department?->departmentName ?? '',
            'subdepartment' => $record->user?->subdepartment?->subDepartmentName ?? '',
            'section' => $record->user?->section?->sectionName ?? '',
            'position' => $record->user?->position?->positionName ?? '',
            'received_date' => $record->received_date ?? '',
            'status' => $record->status?->name ?? '',
            'status_id' => $record->status_id ?? '',

            'bppb_items' => $record?->bppb_item?->map(fn($item) => [
                'id' => $item->id,
                'item_id' => $item->item_id,
                'category' => $item->category?->name ?? '',
                'brand' => $item->brand?->name ?? '',
                'name' => $item->item?->name ?? '',
                'qty' => $item->qty,
                'purchase_order_id' => $item->purchase_order_id,
                'description' => $item->description,
            ])->toArray() ?? [],

            'bppb_inks' => $record?->bppb_ink?->map(fn($ink) => [
                'id' => $ink->id,
                'ink_id' => $ink->ink_id,
                'category' => $ink->category?->name ?? '',
                'brand' => $ink->brand?->name ?? '',
                'name' => $ink->ink?->name ?? '',
                'qty' => $ink->qty,
                'purchase_order_id' => $ink->purchase_order_id,
                'description' => $ink->description,
            ])->toArray() ?? [],

            'bppb_softwares' => $record?->bppb_software?->map(fn($software) => [
                'id' => $software->id,
                'software_id' => $software->software_id,
                'category' => $software->category?->name ?? '',
                'brand' => $software->brand?->name ?? '',
                'name' => $software->software?->name ?? '',
                'qty' => $software->qty,
                'purchase_order_id' => $software->purchase_order_id,
                'description' => $software->description,
                'noBppbPemohon' => $software->noBppbPemohon,
                'pemohonIT' => $software->user?->name ?? '',
                'userPemohon' => $software->userPemohon,
                'departementPemohon' => $software->departementPemohon,
                'lokasiPemohon' => $software->lokasiPemohon,
                'serialNumber' => $software->serialNumber,
            ])->toArray() ?? [],
        ];
    }

    public function redirectToAddBppbItem(int $bppb_id)
    {
        // Generate URL create BPPBItem resource dengan parameter bppb_id
        $url = BppbItemResource::getUrl('create', [
            'bppb_id' => $bppb_id,
        ]);

        return redirect()->to($url);
    }

    public function redirectToAddBppbInk(int $bppb_id)
    {
        // Generate URL create BPPBInk resource dengan parameter bppb_id
        $url = BppbInkResource::getUrl('create', [
            'bppb_id' => $bppb_id,
        ]);

        return redirect()->to($url);
    }

    public function redirectToAddBppbSoftware(int $bppb_id)
    {
        // Generate URL create BPPBSoftware resource dengan parameter bppb_id
        $url = BppbSoftwareResource::getUrl('create', [
            'bppb_id' => $bppb_id,
        ]);

        return redirect()->to($url);
    }

    public function redirectToAddPOBppb(int $bppb_id)
    {
        // Generate URL create PurchaseOrder resource dengan parameter bppb_id
        $url = PurchaseOrderResource::getUrl('create', [
            'bppb_id' => $bppb_id,
        ]);

        return redirect()->to($url);
    }

    public function deleteBppbItem(int $bppbId, int $itemId)
    {
        // Ambil semua item yang akan dihapus
        $items = Bppb_item::where('bppb_id', $bppbId)
            ->where('item_id', $itemId)
            ->get();

        // Hapus satu per satu agar activity log tetap jalan
        $items->each(fn($item) => $item->delete());

        Notification::make()
            ->title('Item berhasil dihapus')
            ->success()
            ->send();

        // Redirect ke halaman edit BPPB via resource
        return redirect(BppbResource::getUrl('edit', ['record' => $bppbId]));
    }
    public function deleteBppbInk(int $bppbId, int $inkId)
    {
        // Ambil semua tinta yang akan dihapus
        $inks = Bppb_ink::where('bppb_id', $bppbId)
            ->where('ink_id', $inkId)
            ->get();

        // Hapus satu per satu agar activity log tetap jalan
        $inks->each(fn($ink) => $ink->delete());

        Notification::make()
            ->title('Tinta berhasil dihapus')
            ->success()
            ->send();

        // Redirect ke halaman edit BPPB via resource
        return redirect(BppbResource::getUrl('edit', ['record' => $bppbId]));
    }
    public function deleteBppbSoftware(int $bppbId, int $softwareId)
    {
        // Ambil semua software yang akan dihapus
        $softwares = Bppb_software::where('bppb_id', $bppbId)
            ->where('software_id', $softwareId)
            ->get();

        // Hapus satu per satu agar activity log tetap tercatat
        $softwares->each(fn($software) => $software->delete());

        Notification::make()
            ->title('Software berhasil dihapus')
            ->success()
            ->send();

        // Redirect ke halaman edit BPPB via resource
        return redirect(BppbResource::getUrl('edit', ['record' => $bppbId]));
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->record($this->record) // Tambahkan ini
                ->visible(fn() => in_array($this->record->status_id, [1, 2, 3])),
            ForceDeleteAction::make()
                ->record($this->record), // Tambahkan ini
            RestoreAction::make()
                ->record($this->record), // Tambahkan ini
            Action::make('approve')
                ->label('Approve')
                ->action('approveRecord')
                ->color('success')
                ->visible(fn() => auth()->user()->hasRole('admin') && $this->record->status_id === 3),
            Action::make('reject')
                ->label('Reject')
                ->action('rejectRecord')
                ->color('warning')
                ->visible(fn() => auth()->user()->hasRole('admin') && $this->record->status_id === 3),
            Action::make('received')
                ->label('Barang Diterima di IT')
                ->tooltip('Klik ini jika BPB sudah dibuat, dan barang sudah di terima di IT')
                ->action('receivedRecord')
                ->color('success')
                ->visible(fn() => auth()->user()->hasRole('admin') && $this->record->status_id === 4),
            Action::make('finish')
                ->label('Selesai')
                ->tooltip('Klik ini semua transaksi BPPB sudah selesai')
                ->action('finishRecord')
                ->color('success')
                ->visible(fn() => ($this->record->status_id === 5 || $this->record->status_id === 7)),
        ];
    }

    public function approveRecord()
    {
        $this->record->update([
            'status_id' => 4,
            'received_date' => Carbon::now(),
            'user_validation_id' => auth()->id(),
        ]);
        Notification::make()
            ->title('Record approved successfully!')
            ->success()
            ->send();
        return redirect(request()->header('Referer'));
    }
    public function rejectRecord()
    {
        $this->record->update([
            'status_id' => 2,
            'received_date' => Carbon::now(),
            'user_validation_id' => auth()->id(),
        ]);
        Notification::make()
            ->title('Record rejected successfully!')
            ->success()
            ->send();
        return redirect(request()->header('Referer'));
    }

    public function receivedRecord()
    {
        $this->record->update([
            'status_id' => 5,
        ]);

        Notification::make()
            ->title('Barang diterima di IT') //mengupdate status menjadi diterima
            ->success()
            ->send();

        return redirect(request()->header('Referer'));
    }

    public function finishRecord()
    {
        $this->record->update([
            'status_id' => 6,
        ]);

        Notification::make()
            ->title('Semua transaksi telah selesai') //mengupdate status menjadi diterima
            ->success()
            ->send();

        return redirect(request()->header('Referer'));
    }

    public function submit(): void
    {
        try {
            // menyimpan data
            $this->record->update($this->data);

            Notification::make()
                ->title('Berhasil')
                ->body('Data berhasil diperbarui.')
                ->success()
                ->send();

            $this->redirect(static::getUrl(['record' => $this->record]));
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Gagal menyimpan data')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    // public function deletePO($bppbId, $poId)
    // {
    //     $po = Purchase_order::find($poId);
    //     $bpb = Bpb::where('po_id', $poId);

    //     if ($bpb) {
    //         $bpb->delete(); // Hapus BPB terkait
    //     }

    //     if ($po) {
    //         // Kosongkan purchase_order_id di semua bppb_items yang terkait
    //         Bppb_item::where('purchase_order_id', $poId)
    //             ->update(['purchase_order_id' => null]);
    //         // Kosongkan purchase_order_id di semua Bppb_inks yang terkait
    //         Bppb_ink::where('purchase_order_id', $poId)
    //             ->update(['purchase_order_id' => null]);
    //         // Kosongkan purchase_order_id di semua Bppb_softwares yang terkait
    //         Bppb_software::where('purchase_order_id', $poId)
    //             ->update(['purchase_order_id' => null]);

    //         // Hapus PO
    //         $po->delete();

    //         Notification::make()
    //             ->title('Purchase Order berhasil dihapus')
    //             ->success()
    //             ->send();
    //     } else {
    //         Notification::make()
    //             ->title('Purchase Order tidak ditemukan')
    //             ->danger()
    //             ->send();
    //     }

    //     return redirect()->route('filament.sis.resources.bppbs.edit', ['record' => $bppbId]);
    // }
    // public function confirmCreateBPB($bppbId, $poId)
    // {
    //     return redirect()->route('filament.sis.resources.bpbs.create', ['bppb_id' => $bppbId, 'po_id' => $poId]);
    // }
    public function confirmEditBppbSoftware($bppbId, $bppbSoftwareId)
    {
        return redirect()->route('filament.sis.resources.bppb-software.edit', ['bppb_id' => $bppbId, 'record' => $bppbSoftwareId]);
    }


    // ___________________________________________________________________________________________Widget
    // protected function getHeaderWidgets(): array
    // {
    //     $record = $this->record;
    //     return [
    //         BppbDetailWidget::make([
    //             'bppb_id' => $record->id ?? '',
    //             'noBppb' => $record->noBppb ?? '',
    //             'name' => $record->user?->name ?? '',
    //             'NIK' => $record->user?->NIK ?? '',
    //             'created_at' => $record->created_at ?? '',
    //             'company' => $record->user?->company?->companyName ?? '',
    //             'regional' => $record->user?->regional?->regionalName ?? '',
    //             'businessunit' => $record->user?->businessunit?->businessUnitName ?? '',
    //             'department' => $record->user?->department?->departmentName ?? '',
    //             'subdepartment' => $record->user?->subdepartment?->subDepartmentName ?? '',
    //             'section' => $record->user?->section?->sectionName ?? '',
    //             'position' => $record->user?->position?->positionName ?? '',
    //             'received_date' => $record->received_date ?? '',
    //             'status' => $record->status?->name ?? '',
    //             'status_id' => $record->status_id ?? '',
    //         ]),
    //     ];
    // }
}
