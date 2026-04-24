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
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\Page;
use Filament\Actions\RestoreAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\ForceDeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

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

        $this->record->load([
            'user.company',
            'user.regional',
            'user.businessunit',
            'user.department',
            'user.subdepartment',
            'user.section',
            'user.position',
            'status',
            'bppb_type',
        ]);

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
            'type_bppb' => $record->bppb_type?->name ?? '-',
            'type_bppb_id' => $record->bppb_type_id ?? null,
            'flow_label' => $record->flow_label,
            'is_software_consolidation' => $record->isSoftwareConsolidation(),
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
            'has_bpb_records' => Bpb::query()
                ->withTrashed()
                ->whereHas('purchase_order', function ($query) use ($record) {
                    $query->withTrashed()->where('bppb_id', $record->id);
                })
                ->exists(),
            'has_expedition_records' => \App\Models\Expedition::query()
                ->withTrashed()
                ->where('bppb_id', $record->id)
                ->exists(),

            'bppb_items' => $record?->bppb_item()
                ->with(['category', 'brand', 'item'])
                ->withoutTrashed()
                ->get()
                ->map(fn($item) => [
                    'id' => $item->id,
                    'item_id' => $item->item_id,
                    'category' => $item->category?->name ?? '',
                    'brand' => $item->brand?->name ?? '',
                    'name' => $item->item?->name ?? '',
                    'qty' => $item->qty,
                    'purchase_order_id' => $item->purchase_order_id,
                    'description' => $item->description,
                ])->toArray(),

            'bppb_inks' => $record?->bppb_ink()
                ->with(['category', 'brand', 'ink'])
                ->withoutTrashed()
                ->get()
                ->map(fn($ink) => [
                    'id' => $ink->id,
                    'ink_id' => $ink->ink_id,
                    'category' => $ink->category?->name ?? '',
                    'brand' => $ink->brand?->name ?? '',
                    'name' => $ink->ink?->name ?? '',
                    'qty' => $ink->qty,
                    'purchase_order_id' => $ink->purchase_order_id,
                    'description' => $ink->description,
                ])->toArray(),

            'bppb_softwares' => $record?->bppb_software()
                ->with(['category', 'brand', 'software', 'user'])
                ->withoutTrashed()
                ->get()
                ->map(fn($software) => [
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
                ])->toArray(),
        ];
    }

    protected function getSourceBppbOptions(): array
    {
        return Bppb::query()
            ->with('user')
            ->where('id', '!=', $this->record->id)
            ->whereHas('bppb_software', function ($query) {
                $query->whereNull('purchase_order_id');
            })
            ->orderByDesc('created_at')
            ->get()
            ->mapWithKeys(function (Bppb $bppb) {
                $label = trim(($bppb->noBppb ?? '-') . ' | ' . ($bppb->user?->name ?? '-'));

                return [$bppb->id => $label];
            })
            ->toArray();
    }

    protected function getAvailableSourceSoftwareOptions(?int $sourceBppbId): array
    {
        if (! $sourceBppbId) {
            return [];
        }

        $sourceBppb = Bppb::query()
            ->with(['user', 'bppb_software.software'])
            ->find($sourceBppbId);

        if (! $sourceBppb) {
            return [];
        }

        return $sourceBppb->bppb_software
            ->whereNull('purchase_order_id')
            ->groupBy('software_id')
            ->mapWithKeys(function ($rows, $softwareId) use ($sourceBppb) {
                $available = $rows->count() - $this->getLinkedSoftwareCount($sourceBppb, (int) $softwareId);

                if ($available <= 0) {
                    return [];
                }

                $name = $rows->first()?->software?->name ?? 'Software';
                $label = "{$name} | Tersedia {$available} pcs | {$sourceBppb->noBppb}";

                return [$softwareId => $label];
            })
            ->toArray();
    }

    protected function getLinkedSoftwareCount(Bppb $sourceBppb, int $softwareId): int
    {
        return Bppb_software::query()
            ->where('software_id', $softwareId)
            ->where('noBppbPemohon', $sourceBppb->noBppb)
            ->where('bppb_id', '!=', $sourceBppb->id)
            ->count();
    }

    protected function normalizeDetailValue(?string $value): string
    {
        $value = trim((string) $value);

        return $value !== '' ? $value : '-';
    }
    public function importSoftwareFromSource(array $data): void
    {
        $sourceBppbId = (int) ($data['source_bppb_id'] ?? 0);
        $softwareId = (int) ($data['software_id'] ?? 0);
        $qty = (int) ($data['qty'] ?? 0);
        $details = array_values($data['details'] ?? []);

        $sourceBppb = Bppb::query()
            ->with(['user.department', 'user.regional'])
            ->find($sourceBppbId);

        if (! $sourceBppb || ! $softwareId || $qty < 1) {
            Notification::make()
                ->title('Data software pemohon tidak valid')
                ->danger()
                ->send();

            return;
        }

        if (count($details) !== $qty) {
            Notification::make()
                ->title('Detail pemohon belum lengkap')
                ->body('Jumlah detail software harus sama dengan Qty Tarik.')
                ->danger()
                ->send();

            return;
        }

        $sourceRows = Bppb_software::query()
            ->with('software')
            ->where('bppb_id', $sourceBppb->id)
            ->where('software_id', $softwareId)
            ->whereNull('purchase_order_id')
            ->orderBy('id')
            ->get();

        $alreadyLinked = $this->getLinkedSoftwareCount($sourceBppb, $softwareId);
        $availableQty = $sourceRows->count() - $alreadyLinked;

        if ($availableQty <= 0) {
            Notification::make()
                ->title('Software dari BPPB pemohon ini sudah seluruhnya ditarik ke BPPB admin ini')
                ->warning()
                ->send();

            return;
        }

        if ($qty > $availableQty) {
            Notification::make()
                ->title('Qty melebihi jumlah yang tersedia')
                ->body("Tersedia {$availableQty} pcs untuk ditarik.")
                ->danger()
                ->send();

            return;
        }

        $firstRow = $sourceRows->first();

        DB::transaction(function () use ($qty, $firstRow, $sourceBppb, $details) {
            for ($i = 0; $i < $qty; $i++) {
                $detail = $details[$i] ?? [];

                Bppb_software::create([
                    'bppb_id' => $this->record->id,
                    'software_id' => $firstRow->software_id,
                    'purchase_order_id' => null,
                    'qty' => 1,
                    'description' => $firstRow->description,
                    'noBppbPemohon' => $sourceBppb->noBppb,
                    'pemohonIT' => $sourceBppb->user_id,
                    'userPemohon' => $this->normalizeDetailValue($detail['userPemohon'] ?? null),
                    'departementPemohon' => $this->normalizeDetailValue($detail['departementPemohon'] ?? null),
                    'lokasiPemohon' => $this->normalizeDetailValue($detail['lokasiPemohon'] ?? null),
                    'serialNumber' => $this->normalizeDetailValue($detail['serialNumber'] ?? null),
                ]);
            }
        });

        Notification::make()
            ->title('Software berhasil ditarik dari BPPB pemohon')
            ->body("{$qty} pcs software berhasil ditambahkan ke {$this->record->noBppb}.")
            ->success()
            ->send();

        $this->redirect(static::getUrl(['record' => $this->record]));
    }

    public function redirectToAddBppbItem(int $bppb_id)
    {
        $url = BppbItemResource::getUrl('create', [
            'bppb_id' => $bppb_id,
        ]);

        return redirect()->to($url);
    }

    public function redirectToAddBppbInk(int $bppb_id)
    {
        $url = BppbInkResource::getUrl('create', [
            'bppb_id' => $bppb_id,
        ]);

        return redirect()->to($url);
    }

    public function redirectToAddBppbSoftware(int $bppb_id)
    {
        $url = BppbSoftwareResource::getUrl('create', [
            'bppb_id' => $bppb_id,
        ]);

        return redirect()->to($url);
    }

    public function redirectToAddPOBppb(int $bppb_id)
    {
        $url = PurchaseOrderResource::getUrl('create', [
            'bppb_id' => $bppb_id,
        ]);

        return redirect()->to($url);
    }

    public function deleteBppbItem(int $bppbId, int $itemId)
    {
        $items = Bppb_item::where('bppb_id', $bppbId)
            ->where('item_id', $itemId)
            ->get();

        if ($items->isEmpty()) {
            Notification::make()
                ->title('Item tidak ditemukan')
                ->danger()
                ->send();
            return;
        }

        $items->each(fn($item) => $item->delete());

        Notification::make()
            ->title('Item berhasil dihapus')
            ->success()
            ->send();

        return redirect(BppbResource::getUrl('edit', ['record' => $bppbId]));
    }

    public function deleteBppbInk(int $bppbId, int $inkId)
    {
        $inks = Bppb_ink::where('bppb_id', $bppbId)
            ->where('ink_id', $inkId)
            ->get();
        if ($inks->isEmpty()) {
            Notification::make()
                ->title('Tinta tidak ditemukan')
                ->danger()
                ->send();
            return;
        }

        $inks->each(fn($ink) => $ink->delete());

        Notification::make()
            ->title('Tinta berhasil dihapus')
            ->success()
            ->send();

        return redirect(BppbResource::getUrl('edit', ['record' => $bppbId]));
    }

    public function deleteBppbSoftware(int $bppbId, int $softwareId)
    {
        $softwares = Bppb_software::where('bppb_id', $bppbId)
            ->where('software_id', $softwareId)
            ->get();
        if ($softwares->isEmpty()) {
            Notification::make()
                ->title('Software tidak ditemukan')
                ->danger()
                ->send();
            return;
        }

        $softwares->each(fn($software) => $software->delete());

        Notification::make()
            ->title('Software berhasil dihapus')
            ->success()
            ->send();

        return redirect(BppbResource::getUrl('edit', ['record' => $bppbId]));
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->record($this->record)
                ->visible(fn() => in_array($this->record->status_id, [1, 2, 3])),
            ForceDeleteAction::make()
                ->record($this->record),
            RestoreAction::make()
                ->record($this->record),
            Action::make('pullSoftwareRequests')
                ->label('Tarik Software Pemohon')
                ->icon('heroicon-m-arrow-down-tray')
                ->color('warning')
                ->visible(fn() => auth()->user()->hasRole('admin') && in_array($this->record->status_id, [1, 2, 3, 4]))
                ->schema([
                    Select::make('source_bppb_id')
                        ->label('BPPB Pemohon')
                        ->options(fn() => $this->getSourceBppbOptions())
                        ->searchable()
                        ->required()
                        ->live(),
                    Select::make('software_id')
                        ->label('Software')
                        ->options(fn(Get $get) => $this->getAvailableSourceSoftwareOptions((int) $get('source_bppb_id')))
                        ->searchable()
                        ->required()
                        ->live(),
                    TextInput::make('qty')
                        ->label('Qty Tarik')
                        ->numeric()
                        ->minValue(1)
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Set $set, Get $get, $state) {
                            $qty = max(0, (int) $state);
                            $existing = array_values($get('details') ?? []);
                            $details = [];

                            for ($i = 0; $i < $qty; $i++) {
                                $details[] = [
                                    'userPemohon' => $existing[$i]['userPemohon'] ?? '-',
                                    'departementPemohon' => $existing[$i]['departementPemohon'] ?? '-',
                                    'lokasiPemohon' => $existing[$i]['lokasiPemohon'] ?? '-',
                                    'serialNumber' => $existing[$i]['serialNumber'] ?? '-',
                                ];
                            }

                            $set('details', $details);
                        }),
                    Repeater::make('details')
                        ->label('Detail Pemohon per Qty')
                        ->schema([
                            TextInput::make('userPemohon')
                                ->label('User Pemohon')
                                ->default('-'),
                            TextInput::make('departementPemohon')
                                ->label('Departemen')
                                ->default('-'),
                            TextInput::make('lokasiPemohon')
                                ->label('Lokasi')
                                ->default('-'),
                            TextInput::make('serialNumber')
                                ->label('Serial Number')
                                ->default('-'),
                        ])
                        ->columns(2)
                        ->addable(false)
                        ->deletable(false)
                        ->reorderable(false)
                        ->collapsible(),
                ])
                ->action(fn(array $data) => $this->importSoftwareFromSource($data)),
            Action::make('approve')
                ->label('Approve')
                ->action('approveRecord')
                ->color('success')
                ->visible(fn() => auth()->user()->hasRole('admin') && $this->record->status_id === 3),
            Action::make('reject')
                ->label('Reject')
                ->color('warning')
                ->schema([
                    Textarea::make('reason')
                        ->label('Alasan Reject')
                        ->required()
                        ->rows(4)
                        ->placeholder('Masukkan alasan kenapa BPPB di-reject'),
                ])
                ->action(fn(array $data) => $this->rejectRecord($data))
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
                ->visible(fn() => in_array($this->record->status_id, [5, 7]) && (auth()->user()->hasRole('admin') || $this->record->user_id === auth()->id())),
            Action::make('Print')
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->schema(fn() => $this->record->print_count > 0 ? [
                    Textarea::make('reason')
                        ->label('Alasan Print Ulang')
                        ->required()
                        ->rows(4)
                        ->placeholder('Masukkan alasan kenapa dokumen ini diprint ulang'),
                ] : [])
                ->action(function (array $data) {
                    $params = [];

                    if ($this->record->print_count > 0) {
                        $params['reason'] = trim((string) ($data['reason'] ?? ''));
                    }

                    return redirect()->to(route('bppb.print', ['id' => $this->record->id] + $params));
                })
                ->visible(
                    fn() =>
                    auth()->user()?->hasRole('admin')
                        || in_array($this->record->status_id, [1, 2, 3])
                ),
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
        return redirect()->back();
    }

    public function rejectRecord(array $data)
    {
        $reason = trim((string) ($data['reason'] ?? ''));

        $this->record->update([
            'status_id' => 2,
            'received_date' => Carbon::now(),
            'user_validation_id' => auth()->id(),
        ]);

        activity()
            ->performedOn($this->record)
            ->causedBy(auth()->user())
            ->withProperties([
                'reason' => $reason,
                'noBppb' => $this->record->noBppb,
            ])
            ->log("rejected: {$reason}");

        Notification::make()
            ->title('BPPB berhasil di-reject')
            ->success()
            ->send();
        return redirect()->back();
    }

    public function receivedRecord()
    {
        $this->record->update([
            'status_id' => 5,
        ]);

        Notification::make()
            ->title('Barang diterima di IT')
            ->success()
            ->send();
        return redirect()->back();
    }

    public function finishRecord()
    {
        $this->record->update([
            'status_id' => 6,
        ]);

        Notification::make()
            ->title('Semua transaksi telah selesai')
            ->success()
            ->send();

        return redirect(request()->header('Referer'));
    }

    public function submit(): void
    {
        try {
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

    public function confirmEditBppbSoftware($bppbId, $bppbSoftwareId)
    {
        return redirect()->route('filament.sis.resources.bppb-software.edit', ['bppb_id' => $bppbId, 'record' => $bppbSoftwareId]);
    }
}
