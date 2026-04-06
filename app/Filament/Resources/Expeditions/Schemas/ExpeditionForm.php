<?php

namespace App\Filament\Resources\Expeditions\Schemas;

use App\Models\Bppb;
use App\Models\Bppb_ink;
use App\Models\Bppb_item;
use App\Models\Bppb_software;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\CheckboxList;

class ExpeditionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('bppb_id')
                    ->label('No. Bppb')
                    ->default(fn() => request()->integer('bppb_id'))
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->searchDebounce(500)
                    ->getSearchResultsUsing(function (string $search) {

                        if (blank($search) || strlen($search) < 2) {
                            return []; // kosong dulu sampai user ketik min 2 huruf
                        }

                        return Bppb::query()
                            ->with('user')
                            ->whereHas('user')
                            ->where('status_id', 5)
                            ->where(function ($query) use ($search) {
                                $query->where('noBppb', 'like', "%{$search}%")
                                    ->orWhereHas('user', function ($q) use ($search) {
                                        $q->where('name', 'like', "%{$search}%")
                                            ->orWhere('NIK', 'like', "%{$search}%");
                                    });
                            })
                            ->limit(20)
                            ->get()
                            ->mapWithKeys(fn($bppb) => [
                                $bppb->id => "{$bppb->noBppb} - {$bppb->user->name} ({$bppb->user->NIK})",
                            ]);
                    })
                    ->getOptionLabelUsing(function ($value) {
                        $bppb = \App\Models\Bppb::with('user')->find($value);

                        return $bppb
                            ? "{$bppb->noBppb} - {$bppb->user->name} ({$bppb->user->NIK})"
                            : null;
                    })
                    ->columnSpanFull(),
                TextInput::make('expeditor')
                    ->label('Karyawan Ekspedisi')
                    ->required(),
                DatePicker::make('dateStart')
                    ->label('Tanggal Ekspedisi')
                    ->required(),
                Textarea::make('description')
                    ->label('Keterangan')
                    ->columnSpanFull()
                    ->rows(3),
                CheckboxList::make('include_items')
                    ->label('Pilih Barang yang Akan Dikirim')
                    ->options(function (callable $get) {
                        $bppbId = $get('bppb_id');
                        $poId = request()->integer('po_id');
                        if (!$bppbId) return [];

                        // Sumber expedisi harus dari item yang sudah punya PO
                        $items = Bppb_item::query()
                            ->where('bppb_id', $bppbId)
                            ->whereNotNull('purchase_order_id')
                            ->when($poId, fn($query) => $query->where('purchase_order_id', $poId))
                            ->with(['item', 'Purchase_order'])
                            ->get()
                            ->groupBy(fn($row) => $row->item_id . '|' . $row->purchase_order_id)
                            ->mapWithKeys(function ($grouped) {
                                $firstItem = $grouped->first();
                                if (!$firstItem || !$firstItem->item || !$firstItem->Purchase_order) return [];

                                $qty = $grouped->count();
                                $item = $firstItem->item;
                                $key = $firstItem->item_id . '|' . $item->product_form_id . '|' . $firstItem->purchase_order_id;
                                $label = "[Item] {$item->name} - qty = {$qty} - No. PO: {$firstItem->Purchase_order->noPo}";
                                return [$key => $label];
                            });

                        $inks = Bppb_ink::query()
                            ->where('bppb_id', $bppbId)
                            ->whereNotNull('purchase_order_id')
                            ->when($poId, fn($query) => $query->where('purchase_order_id', $poId))
                            ->with(['ink', 'Purchase_order'])
                            ->get()
                            ->groupBy(fn($row) => $row->ink_id . '|' . $row->purchase_order_id)
                            ->mapWithKeys(function ($grouped) {
                                $firstInk = $grouped->first();
                                if (!$firstInk || !$firstInk->ink || !$firstInk->Purchase_order) return [];

                                $qty = $grouped->count();
                                $ink = $firstInk->ink;
                                $key = $firstInk->ink_id . '|' . $ink->product_form_id . '|' . $firstInk->purchase_order_id;
                                $label = "[Ink] {$ink->name} - qty = {$qty} - No. PO: {$firstInk->Purchase_order->noPo}";
                                return [$key => $label];
                            });

                        $softwares = Bppb_software::query()
                            ->where('bppb_id', $bppbId)
                            ->whereNotNull('purchase_order_id')
                            ->when($poId, fn($query) => $query->where('purchase_order_id', $poId))
                            ->with(['software', 'Purchase_order'])
                            ->get()
                            ->groupBy(fn($row) => $row->software_id . '|' . $row->purchase_order_id)
                            ->mapWithKeys(function ($grouped) {
                                $firstSoftware = $grouped->first();
                                if (!$firstSoftware || !$firstSoftware->software || !$firstSoftware->Purchase_order) return [];

                                $qty = $grouped->count();
                                $software = $firstSoftware->software;
                                $key = $firstSoftware->software_id . '|' . $software->product_form_id . '|' . $firstSoftware->purchase_order_id;
                                $label = "[Software] {$software->name} - qty = {$qty} - No. PO: {$firstSoftware->Purchase_order->noPo}";
                                return [$key => $label];
                            });
                        $items = collect($items);
                        $inks = collect($inks);
                        $softwares = collect($softwares);
                        return $items->merge($inks)->merge($softwares);
                    })
                    ->bulkToggleable()
                    ->columns(2)
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
