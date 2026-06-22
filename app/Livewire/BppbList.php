<?php

namespace App\Livewire;

use App\Models\Bppb_ink;
use App\Models\Bppb_item;
use App\Models\Bppb_software;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class BppbList extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public ?int $bppbId = null;
    public ?int $statusId = null;

    public function mount($bppbId, $statusId = null)
    {
        $this->bppbId = $bppbId;
        $this->statusId = $statusId;
    }

    public function table(Table $table): Table
    {
        return $table
            ->records(fn() => $this->getRecords())
            ->columns([
                TextColumn::make('name')
                    ->label('Nama'),

                TextColumn::make('qty')
                    ->label('Qty Dipesan')
                    ->alignCenter(),

                TextColumn::make('description')
                    ->label('Keterangan')
                    ->formatStateUsing(fn (?string $state) => filled(trim((string) $state)) ? trim((string) $state) : '-')
                    ->wrap(),

                TextColumn::make('processed')
                    ->label('Qty Diproses')
                    ->alignCenter()
                    ->color(
                        fn($record) =>
                        $record['qty'] == $record['processed']
                            ? 'success'
                            : 'danger'
                    ),

                TextColumn::make('type')
                    ->badge(),
            ])
            ->actions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-m-pencil-square')
                    ->color('warning')
                    ->visible(function () {
                        return in_array($this->statusId, [1, 2, 3])
                            || auth()->user()->hasRole('admin');
                    })
                    ->fillForm(fn($record) => [
                        'qty' => $record['qty'],
                        'description' => $record['description'],
                    ])
                    ->schema([
                        TextInput::make('qty')
                            ->label('Qty')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                        Textarea::make('description')
                            ->label('Keterangan')
                            ->rows(3)
                            ->placeholder('Masukkan keterangan jika diperlukan'),
                    ])
                    ->action(function (array $data, $record) {
                        if ($record['processed'] > 0) {
                            Notification::make()
                                ->title('Tidak bisa diedit')
                                ->body('Data ini sudah diproses ke Purchase Order.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $currentQty = (int) $record['qty'];
                        $newQty = (int) $data['qty'];
                        $newDescription = trim((string) ($data['description'] ?? ''));
                        $newDescription = $newDescription !== '' ? $newDescription : null;

                        if ($record['type'] === 'Item') {
                            $baseQuery = Bppb_item::where('bppb_id', $this->bppbId)
                                ->where('item_id', $record['id']);

                            $firstRow = (clone $baseQuery)
                                ->where('item_id', $record['id'])
                                ->first();

                            if (! $firstRow) {
                                Notification::make()
                                    ->title('Data item tidak ditemukan')
                                    ->danger()
                                    ->send();

                                return;
                            }

                            $baseQuery->update([
                                'description' => $newDescription,
                            ]);

                            if ($newQty > $currentQty) {
                                for ($i = 0; $i < ($newQty - $currentQty); $i++) {
                                    Bppb_item::create([
                                        'bppb_id' => $this->bppbId,
                                        'item_id' => $record['id'],
                                        'purchase_order_id' => null,
                                        'qty' => 1,
                                        'description' => $newDescription,
                                    ]);
                                }
                            } elseif ($newQty < $currentQty) {
                                $rowsToDelete = (clone $baseQuery)
                                    ->whereNull('purchase_order_id')
                                    ->latest('id')
                                    ->take($currentQty - $newQty)
                                    ->get();

                                $rowsToDelete->each->delete();
                            }
                        }

                        if ($record['type'] === 'Ink') {
                            $baseQuery = Bppb_ink::where('bppb_id', $this->bppbId)
                                ->where('ink_id', $record['id']);

                            $firstRow = (clone $baseQuery)
                                ->where('ink_id', $record['id'])
                                ->first();

                            if (! $firstRow) {
                                Notification::make()
                                    ->title('Data tinta tidak ditemukan')
                                    ->danger()
                                    ->send();

                                return;
                            }

                            $baseQuery->update([
                                'description' => $newDescription,
                            ]);

                            if ($newQty > $currentQty) {
                                for ($i = 0; $i < ($newQty - $currentQty); $i++) {
                                    Bppb_ink::create([
                                        'bppb_id' => $this->bppbId,
                                        'ink_id' => $record['id'],
                                        'purchase_order_id' => null,
                                        'qty' => 1,
                                        'description' => $newDescription,
                                    ]);
                                }
                            } elseif ($newQty < $currentQty) {
                                $rowsToDelete = (clone $baseQuery)
                                    ->whereNull('purchase_order_id')
                                    ->latest('id')
                                    ->take($currentQty - $newQty)
                                    ->get();

                                $rowsToDelete->each->delete();
                            }
                        }

                        if ($record['type'] === 'Software') {
                            $baseQuery = Bppb_software::where('bppb_id', $this->bppbId)
                                ->where('software_id', $record['id']);

                            $firstRow = (clone $baseQuery)
                                ->where('software_id', $record['id'])
                                ->first();

                            if (! $firstRow) {
                                Notification::make()
                                    ->title('Data software tidak ditemukan')
                                    ->danger()
                                    ->send();

                                return;
                            }

                            $baseQuery->update([
                                'description' => $newDescription,
                            ]);

                            if ($newQty > $currentQty) {
                                for ($i = 0; $i < ($newQty - $currentQty); $i++) {
                                    Bppb_software::create([
                                        'bppb_id' => $this->bppbId,
                                        'software_id' => $record['id'],
                                        'purchase_order_id' => null,
                                        'qty' => 1,
                                        'description' => $newDescription,
                                        'noBppbPemohon' => $firstRow->noBppbPemohon,
                                        'pemohonIT' => $firstRow->pemohonIT,
                                        'userPemohon' => $firstRow->userPemohon,
                                        'departementPemohon' => $firstRow->departementPemohon,
                                        'lokasiPemohon' => $firstRow->lokasiPemohon,
                                        'serialNumber' => $firstRow->serialNumber,
                                    ]);
                                }
                            } elseif ($newQty < $currentQty) {
                                $rowsToDelete = (clone $baseQuery)
                                    ->whereNull('purchase_order_id')
                                    ->latest('id')
                                    ->take($currentQty - $newQty)
                                    ->get();

                                $rowsToDelete->each->delete();
                            }
                        }

                        $this->resetTable();

                        Notification::make()
                            ->title('Data berhasil diperbarui')
                            ->success()
                            ->send();
                    }),

                Action::make('delete')
                    ->label('Delete')
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(function () {
                        return in_array($this->statusId, [1, 2, 3])
                            || auth()->user()->hasRole('admin');
                    })
                    ->action(function ($record) {
                        if ($record['type'] === 'Item') {
                            Bppb_item::where('bppb_id', $this->bppbId)
                                ->where('item_id', $record['id'])
                                ->delete();
                        }

                        if ($record['type'] === 'Ink') {
                            Bppb_ink::where('bppb_id', $this->bppbId)
                                ->where('ink_id', $record['id'])
                                ->delete();
                        }

                        if ($record['type'] === 'Software') {
                            Bppb_software::where('bppb_id', $this->bppbId)
                                ->where('software_id', $record['id'])
                                ->delete();
                        }

                        $this->resetTable();
                    }),
            ]);
    }

    public function getRecords()
    {
        $data = [];

        /*
        |--------------------------------------------------------------------------
        | ITEM
        |--------------------------------------------------------------------------
        */
        $items = Bppb_item::with(['item' => fn($q) => $q->withTrashed()])
            ->where('bppb_id', $this->bppbId)
            ->get();

        $merged = [];
        $nullCounts = [];

        foreach ($items as $item) {
            if (!isset($merged[$item->item_id])) {
                $merged[$item->item_id] = [
                    'name' => $item->item?->name,
                    'qty' => 0,
                    'processed' => 0,
                    'type' => 'Item',
                    'id' => $item->item_id,
                    'description' => $item->description,
                ];
                $nullCounts[$item->item_id] = 0;
            }

            $merged[$item->item_id]['qty'] += $item->qty;

            if ($item->purchase_order_id === null) {
                $nullCounts[$item->item_id]++;
            }
        }

        foreach ($merged as $id => $item) {
            $item['processed'] = $item['qty'] - $nullCounts[$id];
            $data[] = $item;
        }

        /*
        |--------------------------------------------------------------------------
        | INK
        |--------------------------------------------------------------------------
        */
        $inks = \App\Models\Bppb_ink::with(['ink' => fn($q) => $q->withTrashed()])
            ->where('bppb_id', $this->bppbId)
            ->get();

        $merged = [];
        $nullCounts = [];

        foreach ($inks as $ink) {
            if (!isset($merged[$ink->ink_id])) {
                $merged[$ink->ink_id] = [
                    'name' => $ink->ink?->name,
                    'qty' => 0,
                    'processed' => 0,
                    'type' => 'Ink',
                    'id' => $ink->ink_id,
                    'description' => $ink->description,
                ];
                $nullCounts[$ink->ink_id] = 0;
            }

            $merged[$ink->ink_id]['qty'] += $ink->qty;

            if ($ink->purchase_order_id === null) {
                $nullCounts[$ink->ink_id]++;
            }
        }

        foreach ($merged as $id => $ink) {
            $ink['processed'] = $ink['qty'] - $nullCounts[$id];
            $data[] = $ink;
        }

        /*
        |--------------------------------------------------------------------------
        | SOFTWARE
        |--------------------------------------------------------------------------
        */
        $softwares = \App\Models\Bppb_software::with(['software' => fn($q) => $q->withTrashed()])
            ->where('bppb_id', $this->bppbId)
            ->get();

        $merged = [];
        $nullCounts = [];
        $currentBppb = $this->bppbId ? \App\Models\Bppb::find($this->bppbId) : null;
        $linkedProcessed = [];

        if ($currentBppb && $currentBppb->noBppb) {
            $linkedRows = \App\Models\Bppb_software::where('noBppbPemohon', $currentBppb->noBppb)
                ->whereNotNull('purchase_order_id')
                ->get();

            foreach ($linkedRows as $row) {
                if (!isset($linkedProcessed[$row->software_id])) {
                    $linkedProcessed[$row->software_id] = 0;
                }
                $linkedProcessed[$row->software_id] += $row->qty;
            }
        }

        foreach ($softwares as $software) {
            if (!isset($merged[$software->software_id])) {
                $merged[$software->software_id] = [
                    'name' => $software->software?->name,
                    'qty' => 0,
                    'processed' => 0,
                    'type' => 'Software',
                    'id' => $software->software_id,
                    'description' => $software->description,
                ];
                $nullCounts[$software->software_id] = 0;
            }

            $merged[$software->software_id]['qty'] += $software->qty;

            if ($software->purchase_order_id === null) {
                $nullCounts[$software->software_id]++;
            }
        }

        foreach ($merged as $id => $software) {
            $baseProcessed = $software['qty'] - $nullCounts[$id];
            $software['processed'] = $baseProcessed + ($linkedProcessed[$id] ?? 0);
            $data[] = $software;
        }

        return collect($data);
    }

    public function render(): View
    {
        return view('livewire.bppb-list');
    }
}
