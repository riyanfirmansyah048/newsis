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
                    ->collapsed()
                    ->schema([
                        RepeatableEntry::make('bppb_items')
                            ->label('List Barang')
                            ->schema([
                                TextEntry::make('item.name')
                                    ->label('Nama Barang'),
                                TextEntry::make('qty')
                            ])
                            ->columns(2),
                    ])
                    ->columnSpanFull(),

                // INKS
                Section::make('Daftar Tinta')
                    ->collapsed()
                    ->schema([
                        RepeatableEntry::make('bppb_inks')
                            ->label('List Tinta')
                            ->schema([
                                TextEntry::make('ink.name')
                                    ->label('Nama Tinta'),
                                TextEntry::make('qty'),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpanFull(),

                // SOFTWARES
                Section::make('Daftar Software')
                    ->collapsed()
                    ->schema([
                        RepeatableEntry::make('bppb_softwares')
                            ->label('List Software')
                            ->schema([
                                TextEntry::make('software.name')
                                    ->label('Nama Software'),
                                TextEntry::make('qty'),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpanFull(),

            ]);
    }
}
