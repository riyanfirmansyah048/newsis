<?php

namespace App\Filament\Resources\BppbSoftware\Schemas;

use App\Models\Software;
use App\Models\Brand_software;
use Filament\Schemas\Schema;
use App\Models\Category_software;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class BppbSoftwareForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('bppb_id')
                    ->columnSpanFull()
                    ->default(fn() => request()->get('bppb_id')),
                Select::make('category_software_id')
                    ->label('Kategori Software')
                    ->placeholder('Pilih Kategori Software')
                    ->options(Category_software::all()->pluck('name', 'id'))
                    ->required()
                    ->live()
                    ->searchable()
                    ->afterStateUpdated(function (Set $set) {
                        $set('brand_software_id', null);
                        $set('software_id', null);
                    })
                    ->afterStateHydrated(function (Set $set, Get $get) {
                        $software = Software::find($get('software_id'));
                        if ($software) {
                            $set('category_software_id', $software->category_software_id);
                        }
                    })
                    ->dehydrated(false),
                Select::make('brand_software_id')
                    ->label('Merek Software')
                    ->placeholder('Pilih Merek Software')
                    ->options(fn(Get $get): Collection => Brand_software::query()
                        ->where('category_software_id', $get('category_software_id'))
                        ->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('software_id', null);
                    })
                    ->afterStateHydrated(function (Set $set, Get $get) {
                        $software = Software::find($get('software_id'));
                        if ($software) {
                            $set('brand_software_id', $software->brand_software_id);
                        }
                    })
                    ->dehydrated(false),
                Select::make('software_id')
                    ->label('Nama Software')
                    ->placeholder('Pilih Software')
                    ->options(fn(Get $get): Collection => Software::query()
                        ->where('brand_software_id', $get('brand_software_id'))
                        ->pluck('name', 'id'))
                    ->afterStateHydrated(function (Set $set, Get $get) {
                        $software = Software::find($get('software_id'));
                        if ($software) {
                            $set('software_id', $software->id);
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
                    ->helperText('Opsional, bisa diisi dengan spesifikasi software atau catatan lainnya')
                    ->columnSpanFull(),
            ])
            ->columns(3);
    }
}
