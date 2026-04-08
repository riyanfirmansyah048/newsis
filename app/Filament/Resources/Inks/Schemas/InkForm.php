<?php

namespace App\Filament\Resources\Inks\Schemas;

use App\Models\Type;
use App\Models\Unit;
use App\Models\Brand_ink;
use App\Models\Category_ink;
use App\Models\Product_form;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Collection;

class InkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Tinta')
                    ->required()
                    ->columnSpanFull(),
                Select::make('category_ink_id')
                    ->label('Kategori Tinta')
                    ->placeholder('Pilih Kategori Tinta')
                    ->options(Category_ink::query()->pluck('name', 'id')->toArray())
                    ->default(fn() => request()->integer('category_ink_id'))
                    ->required()
                    ->live()
                    ->searchable()
                    ->preload()
                    ->afterStateUpdated(function (Set $set, $state, $old) {
                        if ($old !== null && $state !== $old) {
                            $set('brand_ink_id', null);
                        }
                    }),
                Select::make('brand_ink_id')
                    ->label('Merek Tinta')
                    ->placeholder('Pilih Merek Tinta')
                    ->options(fn(Get $get): array => Brand_ink::query()
                        ->when(
                            $get('category_ink_id'),
                            fn($query) => $query->where('category_ink_id', $get('category_ink_id'))
                        )
                        ->pluck('name', 'id')
                        ->toArray())
                    ->default(fn() => request()->integer('brand_ink_id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->disabled(fn(Get $get) => blank($get('category_ink_id'))),
                Select::make('product_form_id')
                    ->label('Bentuk Tinta')
                    ->placeholder('Pilih Bentuk Tinta')
                    ->options(Product_form::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                Select::make('type_id')
                    ->label('Jenis Tinta')
                    ->placeholder('Pilih Jenis Tinta')
                    ->options(Type::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                Select::make('unit_id')
                    ->label('Satuan Tinta')
                    ->placeholder('Pilih Satuan Tinta')
                    ->options(Unit::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label('Keterangan')
                    ->columnSpanFull(),
            ]);
    }
}
