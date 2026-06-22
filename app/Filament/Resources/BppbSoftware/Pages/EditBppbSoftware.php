<?php

namespace App\Filament\Resources\BppbSoftware\Pages;

use App\Models\User;
use App\Models\Software;
use Filament\Schemas\Schema;
use App\Models\Brand_software;
use App\Models\Category_software;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Collection;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Actions\ForceDeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Bppbs\BppbResource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use App\Filament\Resources\BppbSoftware\BppbSoftwareResource;

class EditBppbSoftware extends EditRecord
{
    protected static string $resource = BppbSoftwareResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
    // protected function getRedirectUrl(): string
    // {
    //     return BppbResource::getUrl('edit', ['record' => $this->record->bppb_id]);
    // }

    protected function getRedirectUrl(): string
    {
        // Jika admin → balik ke parent BPPB
        if (auth()->user()->hasRole('admin')) {
            return BppbResource::getUrl('edit', ['record' => $this->record->bppb_id]);
        }

        // Jika bukan admin → tetap di halaman edit software
        return static::$resource::getUrl('edit', ['record' => $this->record]);
    }

    protected function afterSave(): void
    {
        $record = $this->record;

        if (! $record->source_bppb_software_id) {
            return;
        }

        \App\Models\Bppb_software::withoutEvents(function () use ($record) {
            \App\Models\Bppb_software::where('id', $record->source_bppb_software_id)
                ->update($record->only([
                    'userPemohon',
                    'departementPemohon',
                    'lokasiPemohon',
                    'serialNumber',
                ]));
        });
    }

    public function form(Schema $schema): Schema
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
                    ->dehydrated(false)
                    ->disabled()
                    ->columnSpan(4),
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
                    ->dehydrated(false)
                    ->disabled()
                    ->columnSpan(4),
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
                    ->searchable()
                    ->disabled()
                    ->columnSpan(4),
                TextInput::make('noBppbPemohon')
                    ->label('No. Bppb Pemohon')
                    ->required()
                    ->columnSpan(6),
                Select::make('pemohonIT')
                    ->label('Pemohon IT')
                    ->required()
                    ->options(
                        User::select('id', 'name', 'NIK')
                            ->get()
                            ->mapWithKeys(fn($u) => [$u->id => $u->name . ' - ' . $u->NIK])
                    )
                    ->columnSpan(6)
                    ->searchable(),
                TextInput::make('userPemohon')
                    ->label('User Pemohon')
                    ->required()
                    ->columnSpan(4),
                TextInput::make('departementPemohon')
                    ->label('Departement Pemohon')
                    ->required()
                    ->columnSpan(4),
                TextInput::make('lokasiPemohon')
                    ->label('Lokasi Pemohon')
                    ->required()
                    ->columnSpan(4),
                TextInput::make('serialNumber')
                    ->label('Serial Number')
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label('Keterangan')
                    ->columnSpanFull(),
            ])
            ->columns(12);
    }
}
