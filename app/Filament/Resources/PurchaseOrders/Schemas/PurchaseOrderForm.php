<?php

namespace App\Filament\Resources\PurchaseOrders\Schemas;

use App\Models\Bppb;
use App\Models\Bppb_item;
use App\Models\Bppb_ink;
use App\Models\Bppb_software;
use App\Models\Vendor;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

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

                    TextInput::make('datePo')
                        ->label('Tanggal Purchase Order')
                        ->type('date')
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
                                ->required(),
                        ])
                        ->columns(2)
                        ->dehydrated(false)
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

                            TextInput::make('qty')
                                ->label('Qty')
                                ->numeric()
                                ->minValue(1)
                                ->required(),
                        ])
                        ->columns(2)
                        ->dehydrated(false)
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

                            TextInput::make('qty')
                                ->label('Qty')
                                ->numeric()
                                ->minValue(1)
                                ->required(),
                        ])
                        ->columns(2)
                        ->dehydrated(false)
                        ->defaultItems(0)
                        ->createItemButtonLabel('Tambah Software')
                        ->helperText('Silakan isi daftar software dalam Purchase Order'),
                ])
                ->columnSpanFull(),
        ]);
    }
}
