<?php

namespace App\Filament\Resources\BppbItems\Schemas;

use App\Models\Item;
use App\Models\Brand;
use App\Models\Category;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Collection;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class BppbItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('bppb_id')
                    ->columnSpanFull()
                    ->default(fn() => request()->get('bppb_id')),
                Select::make('category_id')
                    ->label('Kategori Barang')
                    ->placeholder('Pilih Kategori Barang')
                    ->options(Category::all()->pluck('name', 'id'))
                    ->required()
                    ->live()
                    ->searchable()
                    ->afterStateUpdated(function (Set $set) {
                        $set('brand_id', null);
                        $set('item_id', null);
                    })
                    ->afterStateHydrated(function (Set $set, Get $get) {
                        $item = Item::find($get('item_id'));
                        if ($item) {
                            $set('category_id', $item->category_id);
                        }
                    })
                    ->dehydrated(false),
                Select::make('brand_id')
                    ->label('Merek Barang')
                    ->placeholder('Pilih Merek Barang')
                    ->options(fn(Get $get): Collection => Brand::query()
                        ->where('category_id', $get('category_id'))
                        ->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('item_id', null);
                    })
                    ->afterStateHydrated(function (Set $set, Get $get) {
                        $item = Item::find($get('item_id'));
                        if ($item) {
                            $set('brand_id', $item->brand_id);
                        }
                    })
                    ->dehydrated(false),
                Select::make('item_id')
                    ->label('Nama Barang')
                    ->placeholder('Pilih Barang')
                    ->options(fn(Get $get): Collection => Item::query()
                        ->where('brand_id', $get('brand_id'))
                        ->pluck('name', 'id'))
                    ->afterStateHydrated(function (Set $set, Get $get) {
                        $item = Item::find($get('item_id'));
                        if ($item) {
                            $set('item_id', $item->id);
                        }
                    })
                    ->required()
                    ->live()
                    ->searchable(),
                TextInput::make('qty')
                    ->label('Qty')
                    ->required()
                    ->numeric()
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label('Keterangan')
                    ->helperText('Opsional, bisa diisi dengan spesifikasi barang atau catatan lainnya')
                    ->columnSpanFull(),
            ])
            ->columns(3);
    }
}
