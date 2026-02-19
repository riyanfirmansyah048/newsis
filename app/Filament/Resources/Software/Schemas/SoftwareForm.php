<?php

namespace App\Filament\Resources\Software\Schemas;

use App\Models\Unit;
use App\Models\Type;
use App\Models\Category_software;
use App\Models\Brand_software;
use App\Models\Product_form;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Collection;


class SoftwareForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Software')
                    ->required()
                    ->columnSpanFull(),
                //------------------------------------------------------------
                Select::make('category_software_id')
                    ->label('Kategori Software')
                    ->placeholder('Pilih Kategori Software')
                    ->options(Category_software::all()->pluck('name', 'id'))
                    // ->default(fn() => request()->input('category_software_id'))
                    ->required()
                    ->live()
                    ->searchable()
                    ->afterStateUpdated(function (Set $set) {
                        $set('brand_software_id', null);
                    }),
                Select::make('brand_software_id')
                    ->label('Merek Software')
                    ->placeholder('Pilih Merek Software')
                    ->options(fn(Get $get): Collection => Brand_software::query()
                        ->where('category_software_id', $get('category_software_id'))
                        ->pluck('name', 'id'))
                    // ->default(fn() => request()->input('brand_software_id'))
                    ->required()
                    ->searchable()
                    ->disabled(fn(Get $get) => blank($get('category_software_id'))),
                //------------------------------------------------------------
                Select::make('product_form_id')
                    ->label('Bentuk Software')
                    ->placeholder('Pilih Bentuk Software')
                    ->options(Product_form::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                Select::make('type_id')
                    ->label('Jenis Software')
                    ->placeholder('Pilih Jenis Software')
                    ->options(Type::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                Select::make('unit_id')
                    ->label('Satuan Software')
                    ->placeholder('Pilih Satuan Software')
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
