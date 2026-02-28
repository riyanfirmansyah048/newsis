<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Models\Item;
use App\Models\User;
use App\Models\Brand;
use App\Models\Vendor;
use App\Models\Service;
use App\Models\Category;
use App\Models\Service_type;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use App\Models\Service_solusi;
use App\Models\Service_status;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;

use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\Bppbs\BppbResource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Info Karyawan')
                    ->columnSpanFull()
                    ->description('')
                    ->visible(fn($record) => $record !== null)
                    // ->columns(2)
                    ->schema([
                        ViewField::make('info_karyawan')
                            ->dehydrated(false)
                            ->view('filament.components.info-service')
                            ->viewData(fn(?Service $record) => [
                                'noService' => $record?->noService,
                                'name' => $record?->user?->name,
                                'NIK' => $record?->user?->NIK,
                                'company' => $record?->user?->company?->companyName,
                                'regional' => $record?->user?->regional?->regionalName,
                                'businessunit' => $record?->user?->businessunit?->businessUnitName,
                                'department' => $record?->user?->department?->departmentName,
                                'subdepartment' => $record?->user?->subdepartment?->subDepartmentName,
                                'section' => $record?->user?->section?->sectionName,
                                'position' => $record?->user?->position?->positionName,
                                'ext' => $record?->user?->ext,
                                'created_at' => $record?->created_at,
                            ]),
                    ]),
                Section::make('Info Service')
                    ->columnSpanFull()
                    ->description('')
                    ->schema([
                        Select::make('user_id')
                            ->label('Nama Karyawan')
                            ->searchable()
                            ->getSearchResultsUsing(
                                fn(string $search) =>
                                User::where('name', 'like', "%{$search}%")
                                    ->limit(20)
                                    ->get()
                                    ->mapWithKeys(fn($u) => [$u->id => $u->name . ' - ' . $u->NIK])
                            )
                            ->getOptionLabelUsing(fn($value) => User::find($value)?->name)
                            ->default(auth()->id())
                            ->disabled()
                            ->required(),
                        TextInput::make('serialNumberItem')
                            ->label('No. Seri Barang')
                            ->columnSpanFull(),
                        Hidden::make('user_id')
                            ->default(fn() => auth()->id())
                            ->required(),
                        Select::make('ic_id')
                            ->label('IC yang mengerjakan')
                            ->getSearchResultsUsing(
                                fn(string $search) =>
                                User::where('name', 'like', "%{$search}%")
                                    ->limit(20)
                                    ->get()
                                    ->mapWithKeys(fn($u) => [$u->id => $u->name . ' - ' . $u->NIK])
                            )
                            ->getOptionLabelUsing(fn($value) => User::find($value)?->name)
                            ->columnSpanFull()
                            ->visible(fn(Get $get) => auth()->user()->can('update-service') && $get('status_id') !== 2)
                            ->searchable(),
                        Grid::make(3)
                            ->schema([
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
                            ]),
                        Select::make('type_service_id')
                            ->label('Request Type')
                            ->required()
                            ->columnSpanFull()
                            ->options(Service_type::where('id', 2)->pluck('name', 'id')),
                        Textarea::make('problem')
                            ->label('Deskripsi Masalah')
                            ->required()
                            ->columnSpanFull(),
                        Select::make('solution_id')
                            ->label('Solusi')
                            ->columnSpanFull()
                            ->searchable()
                            ->live() // Memastikan perubahan langsung diperbarui
                            ->visible(fn(Get $get) => auth()->user()->can('update-service') && $get('status_id') !== 2)
                            ->options(Service_solusi::all()->pluck('name', 'id')),
                        Actions::make([
                            Action::make('create_bppb')
                                ->label('Buat BPPB')
                                ->button()
                                ->url(
                                    fn($record) => $record
                                        ? BppbResource::getUrl('create', ['service_id' => $record->id, 'user_service_id' => $record->user_id, 'bppb_type_id' => 3])
                                        : BppbResource::getUrl('create')
                                )
                                ->visible(fn(Get $get) => auth()->user()->can('update-service')
                                    && ($get('solution_id') == 1
                                        || $get('solution_id') == 2
                                        || $get('solution_id') == 3
                                        || $get('solution_id') == 4
                                        || $get('solution_id') == 5))
                                ->openUrlInNewTab(false), // Opsional: true untuk buka di tab baru
                        ]),
                        ViewField::make('list_bppb')
                            ->dehydrated(false)
                            ->view('filament.components.list-bppb')
                            ->viewData(fn(?Service $record) => [
                                'bppbs' => $record?->bppbs ?? collect(),
                            ])
                            ->visible(fn(?Service $record) => $record?->bppbs?->isNotEmpty()),
                        Select::make('vendor_id')
                            ->label('Vendor')
                            ->placeholder('Pilih Vendor')
                            ->visible(fn(Get $get) => auth()->user()->can('update-service')
                                && $get('status_id') !== 2
                                && $get('solution_id') == 2)
                            ->options(Vendor::all()->pluck('vendorName', 'id'))
                            ->columnSpanFull()
                            ->searchable(),

                        Action::make('print_surat_jalan')
                            ->label('Print Surat Jalan')
                            ->icon('heroicon-o-printer')
                            ->color('success')
                            ->url(fn($record) => route('service.print-surat-jalan', $record->id))
                            ->openUrlInNewTab()
                            ->visible(fn(Get $get) => auth()->user()->can('update-service')
                                && $get('status_id') !== 2
                                && $get('solution_id') == 2),

                        Textarea::make('analisa')
                            ->label('Analisa')
                            ->visible(fn(Get $get) => auth()->user()->can('update-service')
                                && ($get('solution_id') == 1
                                    || $get('solution_id') == 2
                                    || $get('solution_id') == 3
                                    || $get('solution_id') == 4
                                    || $get('solution_id') == 5))
                            ->columnSpanFull(),
                        Textarea::make('analisa_reject')
                            ->label('Alasan Reject')
                            ->visible(fn(Get $get) => auth()->user()->can('update-service')
                                && $get('solution_id') == 6)
                            ->columnSpanFull(),
                        Grid::make(4)
                            ->schema([
                                TextInput::make('estimation')
                                    ->label('Estimasi Pengerjaan (dalam angka)')
                                    ->visible(fn(Get $get) => auth()->user()->can('update-service') && $get('status_id') !== 2)
                                    ->suffix('Hari'),
                                DatePicker::make('received_date')
                                    ->label('Tanggal Terima Berkas')
                                    ->visible(fn() => auth()->user()->can('update-service')),
                                DatePicker::make('work_date')
                                    ->label('Tanggal Pengerjaan')
                                    ->visible(fn(Get $get) => auth()->user()->can('update-service') && $get('status_id') !== 2),
                                DatePicker::make('finish_date')
                                    ->label('Tanggal Selesai Service')
                                    ->visible(fn(Get $get) => auth()->user()->can('update-service') && $get('status_id') !== 2),
                            ]),
                    ]),
            ]);
    }
}
