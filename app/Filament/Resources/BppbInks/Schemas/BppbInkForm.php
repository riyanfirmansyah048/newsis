<?php

namespace App\Filament\Resources\BppbInks\Schemas;

use App\Models\Ink;
use App\Models\Brand_ink;
use App\Models\Category_ink;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class BppbInkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('bppb_id')
                    ->columnSpanFull()
                    ->default(fn() => request()->get('bppb_id')),
                // Select::make('category_ink_id')
                //     ->label('Kategori Tinta')
                //     ->placeholder('Pilih Kategori Tinta')
                //     ->options(Category_ink::all()->pluck('name', 'id'))
                //     ->required()
                //     ->live()
                //     ->searchable()
                //     ->afterStateUpdated(function (Set $set) {
                //         $set('brand_ink_id', null);
                //         $set('ink_id', null);
                //     })
                //     ->afterStateHydrated(function (Set $set, Get $get) {
                //         $ink = Ink::find($get('ink_id'));
                //         if ($ink) {
                //             $set('category_ink_id', $ink->category_ink_id);
                //         }
                //     })
                //     ->dehydrated(false),
                // Select::make('brand_ink_id')
                //     ->label('Merek Tinta')
                //     ->placeholder('Pilih Merek Tinta')
                //     ->options(fn(Get $get): Collection => Brand_ink::query()
                //         ->where('category_ink_id', $get('category_ink_id'))
                //         ->pluck('name', 'id'))
                //     ->required()
                //     ->searchable()
                //     ->live()
                //     ->afterStateUpdated(function (Set $set) {
                //         $set('ink_id', null);
                //     })
                //     ->afterStateHydrated(function (Set $set, Get $get) {
                //         $ink = Ink::find($get('ink_id'));
                //         if ($ink) {
                //             $set('brand_ink_id', $ink->brand_ink_id);
                //         }
                //     })
                //     ->dehydrated(false),
                // Select::make('ink_id')
                //     ->label('Nama Tinta')
                //     ->placeholder('Pilih Tinta')
                //     ->options(fn(Get $get): Collection => Ink::query()
                //         ->where('brand_ink_id', $get('brand_ink_id'))
                //         ->pluck('name', 'id'))
                //     ->afterStateHydrated(function (Set $set, Get $get) {
                //         $ink = Ink::find($get('ink_id'));
                //         if ($ink) {
                //             $set('ink_id', $ink->id);
                //         }
                //     })
                //     ->required()
                //     ->live()
                //     ->searchable(),
                Select::make('ink_id')
                    ->label('Nama Tinta')
                    ->placeholder('Pilih Nama Tinta...')
                    ->searchable()
                    ->live()
                    ->getSearchResultsUsing(function (string $search): array {
                        return Ink::query()
                            ->where('name', 'like', "%{$search}%")
                            ->limit(20)
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->getOptionLabelUsing(function ($value): ?string {
                        return Ink::find($value)?->name;
                    })
                    ->columnSpanFull()
                    ->required(),
                TextInput::make('qty')
                    ->label('Qty')
                    ->required()
                    ->numeric()
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label('Keterangan')
                    ->helperText('Opsional, bisa diisi dengan spesifikasi Tinta atau catatan lainnya')
                    ->columnSpanFull(),
            ])
            ->columns(3);
    }
}
