<?php

namespace App\Filament\Resources\Expeditions\Schemas;

use App\Models\Bppb;
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
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->searchDebounce(500)
                    ->getSearchResultsUsing(function (string $search) {

                        if (blank($search) || strlen($search) < 2) {
                            return []; // 🔥 kosong dulu sampai user ketik min 2 huruf
                        }

                        // return Bppb::query()
                        //     ->with('user')
                        //     ->whereHas('user')
                        //     ->where(function ($query) use ($search) {
                        //         $query->where('noBppb', 'like', "%{$search}%")
                        //             ->orWhereHas('user', function ($q) use ($search) {
                        //                 $q->where('name', 'like', "%{$search}%")
                        //                     ->orWhere('NIK', 'like', "%{$search}%");
                        //             });
                        //     })
                        //     ->limit(20)
                        //     ->get()
                        //     ->mapWithKeys(fn($bppb) => [
                        //         $bppb->id => "{$bppb->noBppb} - {$bppb->user->name} ({$bppb->user->NIK})",
                        //     ]);
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
                        if (!$bppbId) return [];

                        // Bppb_item
                        $items = \App\Models\Bppb_item::query()
                            ->where('bppb_id', $bppbId)
                            ->get()
                            ->groupBy('item_id')
                            ->mapWithKeys(function ($grouped, $itemId) {
                                $firstItem = $grouped->first();
                                if (!$firstItem || !$firstItem->item) return [];

                                $qty = $grouped->count();
                                $item = $grouped->first()->item;
                                // $key = $grouped->first()->item_id . '|' . $item->product_form_id;
                                $key = $itemId . '|' . $item->product_form_id;
                                $label = "[Item] {$item->name} - qty = {$qty}";
                                return [$key => $label];
                            });

                        // Bppb_ink
                        $inks = \App\Models\Bppb_ink::query()
                            ->where('bppb_id', $bppbId)
                            ->get()
                            ->groupBy('ink_id')
                            ->mapWithKeys(function ($grouped, $inkId) {
                                $firstInk = $grouped->first();
                                if (!$firstInk || !$firstInk->ink) return [];

                                $qty = $grouped->count();
                                $ink = $grouped->first()->ink;
                                // $key = $grouped->first()->id . '|' . $ink->product_form_id;
                                $key = $inkId . '|' . $ink->product_form_id;
                                $label = "[Ink] {$ink->name} - qty = {$qty}";
                                return [$key => $label];
                            });

                        // Bppb_software
                        $softwares = \App\Models\Bppb_software::query()
                            ->where('bppb_id', $bppbId)
                            ->get()
                            ->groupBy('software_id')
                            ->mapWithKeys(function ($grouped, $softwareId) {
                                $firstSoftware = $grouped->first();
                                if (!$firstSoftware || !$firstSoftware->software) return [];

                                $qty = $grouped->count();
                                $software = $grouped->first()->software;
                                // $key = $grouped->first()->id . '|' . $software->product_form_id;
                                $key = $softwareId . '|' . $software->product_form_id;
                                $label = "[Software] {$software->name} - qty = {$qty}";
                                return [$key => $label];
                            });
                        $items = collect($items);
                        $inks = collect($inks);
                        $softwares = collect($softwares);
                        return $items->merge($inks)->merge($softwares);
                    })
                    ->bulkToggleable() // untuk bisa select/deselect semua
                    ->columns(2) // biar rapi
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
