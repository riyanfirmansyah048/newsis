<?php

namespace App\Filament\Resources\PurchaseOrders\Pages;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Schemas\Components\Grid;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use App\Filament\Resources\PurchaseOrders\PurchaseOrderResource;

class ViewPurchaseOrder extends ViewRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Purchase Order (' . $this->record->noPo . ')';
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Purchase Order')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('noPo')
                                ->label('No Purchase Order'),

                            TextEntry::make('datePo')
                                ->label('Tanggal Purchase Order')
                                ->date('d F Y'),

                            TextEntry::make('bppb.noBppb')
                                ->label('No BPPB'),
                        ]),
                    ])
                    ->columnSpanFull(),


                // ITEMS
                Section::make('Daftar Barang')
                    ->collapsed(false)
                    ->schema([
                        RepeatableEntry::make('grouped_items')
                            ->label('List Barang')
                            ->state(function () {

                                return $this->record
                                    ->bppb_items()
                                    ->with('item') // eager load langsung dari query
                                    ->get()
                                    ->groupBy('item_id')
                                    ->map(function ($group) {

                                        return [
                                            'name' => $group->first()->item->name ?? '-',
                                            'total_qty' => $group->count(), // atau sum('qty')
                                        ];
                                    })
                                    ->values()
                                    ->toArray();
                            })
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Nama Barang'),

                                TextEntry::make('total_qty')
                                    ->label('Qty'),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpanFull(),

                // INKS
                Section::make('Daftar Tinta')
                    ->collapsed(false)
                    ->schema([
                        RepeatableEntry::make('grouped_inks')
                            ->label('List Tinta')
                            ->state(function () {

                                return $this->record
                                    ->bppb_inks()
                                    ->with('ink')
                                    ->get()
                                    ->groupBy('ink_id')
                                    ->map(function ($group) {

                                        return [
                                            'name' => $group->first()->ink->name ?? '-',
                                            'total_qty' => $group->count(), // atau sum('qty')
                                        ];
                                    })
                                    ->values()
                                    ->toArray();
                            })
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Nama Tinta'),

                                TextEntry::make('total_qty')
                                    ->label('Qty'),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpanFull(),

                // SOFTWARES
                Section::make('Daftar Software')
                    ->collapsed(false)
                    ->schema([
                        RepeatableEntry::make('grouped_softwares')
                            ->label('List Software')
                            ->state(function () {

                                return $this->record
                                    ->bppb_softwares()
                                    ->with('software')
                                    ->get()
                                    ->groupBy('software_id')
                                    ->map(function ($group) {

                                        return [
                                            'name' => $group->first()->software->name ?? '-',
                                            'total_qty' => $group->count(), // atau sum('qty')
                                        ];
                                    })
                                    ->values()
                                    ->toArray();
                            })
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Nama Software'),

                                TextEntry::make('total_qty')
                                    ->label('Qty'),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpanFull(),

            ]);
    }
}
