<?php

namespace App\Filament\Resources\PurchaseOrders\Schemas;

use App\Models\Bppb_ink;
use App\Models\Bppb_item;
use App\Models\Bppb_software;
use App\Models\Bppb;
use App\Models\Vendor;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PurchaseOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            /* =========================
             * Informasi Purchase Order
             * ========================= */
            Section::make('Informasi Purchase Order')
                ->schema([
                    Hidden::make('bppb_id')
                        ->required()
                        ->default(fn() => request()->get('bppb_id')),

                    Hidden::make('user_id')
                        ->required()
                        ->default(
                            fn(callable $get) =>
                            Bppb::find($get('bppb_id'))?->user_id
                        ),

                    TextInput::make('noPo')
                        ->label('No. PO')
                        ->required(),

                    Select::make('vendor_id')
                        ->label('Vendor')
                        ->options(
                            fn() =>
                            Vendor::query()->pluck('vendorName', 'id')
                        )
                        ->searchable()
                        ->required(),

                    DatePicker::make('datePo')
                        ->label('Tanggal Purchase Order')
                        ->required(),
                ])
                ->columns(3)
                ->columnSpanFull(),

            /* =========================
             * Daftar Barang
             * ========================= */
            Section::make('Daftar Barang')
                ->collapsed()
                ->schema([
                    Repeater::make('po_items')
                        ->schema([
                            Select::make('item_id')
                                ->label('Barang')
                                ->options(function (callable $get) {
                                    $bppbId = $get('../../bppb_id');
                                    $currentItemId = $get('item_id');

                                    return Bppb_item::query()
                                        ->where('bppb_id', $bppbId)
                                        ->where(function ($q) use ($currentItemId) {
                                            $q->whereNull('purchase_order_id');

                                            if ($currentItemId) {
                                                $q->orWhere('item_id', $currentItemId);
                                            }
                                        })
                                        ->with('item')
                                        ->get()
                                        ->groupBy('item_id')
                                        ->map(
                                            fn($group) =>
                                            $group->first()->item->name .
                                                ' (Qty belum diproses: ' . $group->count() . ')'
                                        )
                                        ->toArray();
                                })
                                ->searchable()
                                ->required(),

                            TextInput::make('qty')
                                ->label('Qty')
                                ->numeric()
                                ->minValue(1)
                                ->required()
                                ->live()
                                ->rules([
                                    function (callable $get) {
                                        return function (string $attribute, $value, \Closure $fail) use ($get) {

                                            $bppbId = $get('../../bppb_id');
                                            $itemId = $get('item_id');

                                            if (!$itemId || !$bppbId) {
                                                return;
                                            }

                                            $sisaQty = \App\Models\Bppb_item::query()
                                                ->where('bppb_id', $bppbId)
                                                ->where('item_id', $itemId)
                                                ->whereNull('purchase_order_id')
                                                ->count(); // atau sum('qty') kalau memang pakai qty kolom

                                            if ($value > $sisaQty) {
                                                $fail("Qty melebihi jumlah yang belum diproses. Sisa tersedia: {$sisaQty}");
                                            }
                                        };
                                    },
                                ]),
                        ])
                        ->columns(2)
                        // ->dehydrated(false)
                        ->defaultItems(0)
                        ->createItemButtonLabel('Tambah Barang')
                        ->helperText('Silakan isi daftar barang dalam Purchase Order'),
                ])
                ->columnSpanFull(),

            /* =========================
             * Daftar Tinta
             * ========================= */
            Section::make('Daftar Tinta')
                ->collapsed()
                ->schema([
                    Repeater::make('po_inks')
                        ->schema([
                            Select::make('ink_id')
                                ->label('Tinta')
                                ->options(function (callable $get) {
                                    $bppbId = $get('../../bppb_id');
                                    $currentInkId = $get('ink_id');

                                    return Bppb_ink::query()
                                        ->where('bppb_id', $bppbId)
                                        ->where(function ($q) use ($currentInkId) {
                                            $q->whereNull('purchase_order_id');

                                            if ($currentInkId) {
                                                $q->orWhere('ink_id', $currentInkId);
                                            }
                                        })
                                        ->with('ink')
                                        ->get()
                                        ->groupBy('ink_id')
                                        ->map(
                                            fn($group) =>
                                            $group->first()->ink->name .
                                                ' (Qty belum diproses: ' . $group->count() . ')'
                                        )
                                        ->toArray();
                                })
                                ->searchable()
                                ->required(),

                            // TextInput::make('qty')
                            //     ->label('Qty')
                            //     ->numeric()
                            //     ->minValue(1)
                            //     ->required(),
                            TextInput::make('qty')
                                ->label('Qty')
                                ->numeric()
                                ->minValue(1)
                                ->required()
                                ->live() // supaya realtime
                                ->rules([
                                    function (callable $get) {
                                        return function (string $attribute, $value, \Closure $fail) use ($get) {

                                            $inkId = $get('ink_id');
                                            $bppbId = $get('../../bppb_id');

                                            if (!$inkId || !$bppbId) {
                                                return;
                                            }

                                            $sisaQty = \App\Models\Bppb_ink::query()
                                                ->where('bppb_id', $bppbId)
                                                ->where('ink_id', $inkId)
                                                ->whereNull('purchase_order_id')
                                                ->count();

                                            if ($value > $sisaQty) {
                                                $fail("Qty melebihi jumlah yang belum diproses. Sisa tersedia: {$sisaQty}");
                                            }
                                        };
                                    },
                                ]),
                        ])
                        ->columns(2)
                        // ->dehydrated(false)
                        ->defaultItems(0)
                        ->createItemButtonLabel('Tambah Tinta')
                        ->helperText('Silakan isi daftar tinta dalam Purchase Order'),
                ])
                ->columnSpanFull(),

            /* =========================
             * Daftar Software
             * ========================= */
            Section::make('Daftar Software')
                ->collapsed()
                ->schema([
                    Repeater::make('po_softwares')
                        ->schema([
                            Select::make('software_id')
                                ->label('Software')
                                ->options(function (callable $get) {
                                    $bppbId = $get('../../bppb_id');
                                    $currentSoftwareId = $get('software_id');

                                    return Bppb_software::query()
                                        ->where('bppb_id', $bppbId)
                                        ->where(function ($q) use ($currentSoftwareId) {
                                            $q->whereNull('purchase_order_id');

                                            if ($currentSoftwareId) {
                                                $q->orWhere('software_id', $currentSoftwareId);
                                            }
                                        })
                                        ->with('software')
                                        ->get()
                                        ->groupBy('software_id')
                                        ->map(
                                            fn($group) =>
                                            $group->first()->software->name .
                                                ' (Qty belum diproses: ' . $group->count() . ')'
                                        )
                                        ->toArray();
                                })
                                ->searchable()
                                ->required(),

                            // TextInput::make('qty')
                            //     ->label('Qty')
                            //     ->numeric()
                            //     ->minValue(1)
                            //     ->required(),

                            TextInput::make('qty')
                                ->label('Qty')
                                ->numeric()
                                ->minValue(1)
                                ->required()
                                ->live()
                                ->rules([
                                    function (callable $get) {
                                        return function (string $attribute, $value, \Closure $fail) use ($get) {

                                            $softwareId = $get('software_id');
                                            $bppbId = $get('../../bppb_id');

                                            if (!$softwareId || !$bppbId) {
                                                return;
                                            }

                                            $sisaQty = \App\Models\Bppb_software::query()
                                                ->where('bppb_id', $bppbId)
                                                ->where('software_id', $softwareId)
                                                ->whereNull('purchase_order_id')
                                                ->count();

                                            if ($value > $sisaQty) {
                                                $fail("Qty melebihi jumlah yang belum diproses. Sisa tersedia: {$sisaQty}");
                                            }
                                        };
                                    },
                                ]),
                        ])
                        ->columns(2)
                        // ->dehydrated(false)
                        ->defaultItems(0)
                        ->createItemButtonLabel('Tambah Software')
                        ->helperText('Silakan isi daftar software dalam Purchase Order'),
                ])
                ->columnSpanFull(),
        ]);
    }
}
