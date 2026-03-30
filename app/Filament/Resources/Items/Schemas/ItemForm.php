<?php

namespace App\Filament\Resources\Items\Schemas;

use App\Models\Type;
use App\Models\Unit;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product_form;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class ItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // ------------------------------------------------------------
            TextInput::make('name')
                ->label('Nama Barang')
                ->required()
                ->columnSpanFull(),

            // ------------------------------------------------------------
            Select::make('category_id')
                ->label('Kategori Barang')
                ->placeholder('Pilih Kategori Barang')
                ->options(
                    fn() =>
                    Category::query()
                        ->pluck('name', 'id')
                        ->toArray()
                )
                ->default(fn() => request()->integer('category_id'))
                ->required()
                ->searchable()
                ->live()
                ->afterStateUpdated(
                    function (Set $set, $state, $old) {
                        if ($old !== null && $state !== $old) {
                            $set('brand_id', null);
                        }
                    }
                ),

            // ------------------------------------------------------------
            Select::make('brand_id')
                ->label('Merek Barang')
                ->placeholder('Pilih Merek Barang')
                ->options(
                    fn(Get $get): array =>
                    Brand::query()
                        ->when(
                            $get('category_id'),
                            fn($q) => $q->where('category_id', $get('category_id'))
                        )
                        ->pluck('name', 'id')
                        ->toArray()
                )
                ->default(fn() => request()->integer('brand_id'))
                ->required()
                ->searchable()
                ->disabled(fn(Get $get) => blank($get('category_id'))),

            // ------------------------------------------------------------
            Select::make('product_form_id')
                ->label('Bentuk Barang')
                ->placeholder('Pilih Bentuk Barang')
                ->options(
                    fn() =>
                    Product_form::query()
                        ->pluck('name', 'id')
                        ->toArray()
                )
                ->required()
                ->searchable(),

            // ------------------------------------------------------------
            Select::make('type_id')
                ->label('Jenis Barang')
                ->placeholder('Pilih Jenis Barang')
                ->options(
                    fn() =>
                    Type::query()
                        ->pluck('name', 'id')
                        ->toArray()
                )
                ->required()
                ->searchable(),

            // ------------------------------------------------------------
            Select::make('unit_id')
                ->label('Satuan Barang')
                ->placeholder('Pilih Satuan Barang')
                ->options(
                    fn() =>
                    Unit::query()
                        ->pluck('name', 'id')
                        ->toArray()
                )
                ->required()
                ->searchable()
                ->columnSpanFull(),

            // ------------------------------------------------------------
            Textarea::make('description')
                ->label('Keterangan')
                ->columnSpanFull(),
        ]);
    }
}
