<?php

namespace App\Filament\Resources\Bppbs\Schemas;

use App\Models\Ink;

use Filament\Forms;
use App\Models\Item;
use App\Models\User;
use App\Models\Brand;
use App\Models\Brand_ink;
use App\Models\Vendor;
// use App\Models\Section;
use App\Models\Category;
use App\Models\Software;
use App\Models\Brand_software;
use App\Models\Category_ink;
use App\Models\Category_software;
use Filament\Schemas\Schema;
use App\Models\Purchase_order;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class BppbForm
{
    public static function configure(Schema $schema): Schema
    {
        $record = $schema->getRecord();
        return $schema
            ->components([
                Section::make('Informasi BPPB')
                    ->columnSpanFull()
                    ->schema([
                        ViewField::make('info_bppb')
                            ->dehydrated(false)
                            ->visible(fn($record) => $record !== null)
                            ->view('filament.components.info-bppb')
                            ->viewData([
                                'noBppb' => $record->noBppb ?? '',
                                'name' => $record->user->name ?? '',
                                'NIK' => $record->user->NIK ?? '',
                                'created_at' => $record->created_at ?? '',
                                'company' => $record->user->company->companyName ?? '',
                                'regional' => $record->user->regional->regionalName ?? '',
                                'businessunit' => $record->user->businessunit->businessUnitName ?? '',
                                'department' => $record->user->department->departmentName ?? '',
                                'subdepartment' => $record->user->subdepartment->subDepartmentName ?? '',
                                'section' => $record->user->section->sectionName ?? '',
                                'position' => $record->user->position->positionName ?? '',
                                'received_date' => $record->received_date ?? '',
                                'status' => $record->status->name ?? '',
                                'status_id' => $record->status_id ?? '',
                                'bppb_items' => $record ? $record->bppb_item->map(function ($item) {
                                    return [
                                        'id' => $item->id,
                                        'item_id' => $item->item_id,
                                        'category' => $item->category->name ?? '',
                                        'brand' => $item->brand->name ?? '',
                                        'name' => $item->item->name ?? '',
                                        'qty' => $item->qty,
                                        'purchase_order_id' => $item->purchase_order_id,
                                        'description' => $item->description,
                                    ];
                                })->toArray() : [],
                                'bppb_inks' => $record ? $record->bppb_ink->map(function ($ink) {
                                    return [
                                        'id' => $ink->id,
                                        'ink_id' => $ink->ink_id,
                                        'category' => $ink->category->name ?? '',
                                        'brand' => $ink->brand->name ?? '',
                                        'name' => $ink->ink->name ?? '',
                                        'qty' => $ink->qty,
                                        'purchase_order_id' => $ink->purchase_order_id,
                                        'description' => $ink->description,
                                    ];
                                })->toArray() : [],
                                'bppb_softwares' => $record ? $record->bppb_software->map(function ($software) {
                                    return [
                                        'id' => $software->id,
                                        'software_id' => $software->software_id,
                                        'category' => $software->category->name ?? '',
                                        'brand' => $software->brand->name ?? '',
                                        'name' => $software->software->name ?? '',
                                        'qty' => $software->qty,
                                        'purchase_order_id' => $software->purchase_order_id,
                                        'description' => $software->description,
                                    ];
                                })->toArray() : [],
                                'purchase_orders' => $record ? $record->purchase_orders->map(function ($po) {
                                    return [
                                        'id' => $po->id,
                                        'bppb_id' => $po->bppb_id,
                                        'noPo' => $po->noPo,
                                        'vendor' => $po->vendor->vendorName ?? '',
                                        'datePo' => $po->datePo,
                                    ];
                                })->toArray() : [],
                                'bpb' => $record ? $record->purchase_orders->flatMap->bpb->map(function ($bpb) {
                                    return [
                                        'id' => $bpb->id,
                                        'noBpb' => $bpb->noBpb,
                                        'user_id' => $bpb->user_id,
                                        'po_id' => $bpb->po_id,
                                        'created_at' => $bpb->created_at,
                                    ];
                                })->toArray() : [],

                            ]),
                        Toggle::make('manual_no_bppb')
                            ->label('Input No. BPPB Manual')
                            ->visible(fn() => auth()->user()->hasRole('admin'))
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, $state) {
                                if (!$state) {
                                    $set('noBppb', null);
                                    $set('bppb_type_id', 1);
                                    $set('user_id', auth()->id()); // Set otomatis user aktif
                                } else {
                                    $set('bppb_type_id', 2);
                                    $set('user_id', null);
                                }
                            })
                            ->dehydrated(false),

                        // Hidden user_id (untuk toggle OFF)
                        Hidden::make('user_id')
                            ->default(fn(Get $get) => request()->query('user_service_id') ?: (!$get('manual_no_bppb') ? auth()->id() : null))
                            ->visible(fn(Get $get) => !$get('manual_no_bppb')) // hanya muncul saat toggle off
                            ->dehydrated(fn(Get $get) => !$get('manual_no_bppb')), // hanya dikirim saat toggle off

                        // Select user_id (untuk toggle ON)
                        Select::make('user_id')
                            ->label('Nama Karyawan')
                            ->searchable()
                            ->required()
                            ->getSearchResultsUsing(
                                fn(string $search) =>
                                User::where('name', 'like', "%{$search}%")
                                    ->orWhere('NIK', 'like', "%{$search}%")
                                    ->limit(20)
                                    ->get()
                                    ->mapWithKeys(fn($user) => [
                                        $user->id => $user->name . ' - ' . $user->NIK,
                                    ])
                            )
                            ->getOptionLabelUsing(fn($value) => User::find($value)?->name)
                            ->visible(fn(Get $get) => $get('manual_no_bppb')) // hanya tampil saat toggle on
                            ->dehydrated(fn(Get $get) => $get('manual_no_bppb')) // hanya dikirim saat toggle on
                            ->reactive(),
                        // Hidden::make('user_id')
                        //     ->default(fn() => request()->query('user_service_id') ?: auth()->id()),
                        TextInput::make('noBppb')
                            ->label('No. BPPB')
                            ->placeholder('Masukkan No. BPPB')
                            ->unique(ignoreRecord: true)
                            ->visible(fn(Get $get) => $get('manual_no_bppb'))
                            ->required(fn(Get $get) => $get('manual_no_bppb')),
                        Hidden::make('bppb_type_id')
                            ->default(1), // Default value is 1
                        Textarea::make('description')
                            ->label('Keterangan BPPB')
                            ->helperText('Opsional, bisa diisi dengan catatan atau informasi tambahan terkait BPPB'),
                        // Hidden::make('bppb_type_id')
                        //     ->default(fn() => request()->query('bppb_type_id') ?? 1),
                        Hidden::make('service_id')
                            ->default(fn() => request()->query('service_id')),
                        Hidden::make('user_service_id')
                            ->default(fn() => request()->query('user_service_id')),
                    ]),

                Section::make('Daftar Barang')
                    ->columnSpanFull()
                    ->collapsed()
                    ->schema([
                        Repeater::make('bppb_item')
                            ->relationship('bppb_item')
                            ->label('')
                            ->helperText('Silakan isi daftar barang yang akan diajukan')
                            ->schema([
                                Select::make('item_id')
                                    ->label('Nama Barang')
                                    ->placeholder('Pilih Nama Barang...')
                                    ->searchable()
                                    ->live()
                                    ->getSearchResultsUsing(function (string $search): array {
                                        return Item::query()
                                            ->where('name', 'like', "%{$search}%")
                                            ->limit(20)
                                            ->pluck('name', 'id')
                                            ->toArray();
                                    })
                                    ->getOptionLabelUsing(function ($value): ?string {
                                        return Item::find($value)?->name;
                                    })
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->required(),
                                TextInput::make('qty')
                                    ->label('Qty')
                                    ->required()
                                    ->numeric(),
                                Textarea::make('description')
                                    ->label('Keterangan')
                                    ->helperText('Opsional, bisa diisi dengan spesifikasi barang atau catatan lainnya')
                                    ->columnSpanFull(),
                                Select::make('purchase_order_id')
                                    ->visible(fn($record) => $record !== null)
                                    ->label('Purchase Order')
                                    // ->required()
                                    ->placeholder('Pilih Purchase Order')
                                    ->options(fn(Get $get): Collection => Purchase_order::query()
                                        ->where('bppb_id', $record->id)
                                        ->get()
                                        ->mapWithKeys(function ($po) {
                                            return [$po->id => $po->noPo . ' - ' . $po->vendor->vendorName];
                                        }))
                                    ->columnSpanFull()
                                    ->createOptionForm([
                                        Hidden::make('bppb_id')
                                            ->default(fn() => $record ? $record->id : null),
                                        Hidden::make('user_id')
                                            ->default(auth()->id()),
                                        TextInput::make('noPo')
                                            ->label('No. PO')
                                            ->required()
                                            ->columnSpanFull(),
                                        Select::make('vendor_id')
                                            ->label('Vendor')
                                            ->placeholder('Pilih Vendor')
                                            ->options(Vendor::all()->pluck('vendorName', 'id'))
                                            ->required()
                                            ->searchable(),
                                        TextInput::make('datePo')
                                            ->label('Tanggal PO')
                                            ->required()
                                            ->columnSpanFull()
                                            ->type('date'),
                                    ])
                                    ->createOptionUsing(function (array $data) {
                                        return Purchase_order::create($data)->id;
                                    })
                                    ->searchable(),
                            ])
                            ->default([])
                            ->columns(2)
                            ->maxItems(15)
                            ->createItemButtonLabel('Tambah Barang'),
                    ]),

                Section::make('Daftar Tinta')
                    ->columnSpanFull()
                    ->collapsed()
                    ->schema([
                        Repeater::make('bppb_ink')
                            ->relationship('bppb_ink')
                            ->label('')
                            ->helperText('Silakan isi daftar Tinta yang akan diajukan')
                            ->schema([
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
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->required(),
                                TextInput::make('qty')
                                    ->label('Qty')
                                    ->required()
                                    ->numeric(),
                                Textarea::make('description')
                                    ->label('Keterangan')
                                    ->helperText('Opsional, bisa diisi dengan spesifikasi Tinta atau catatan lainnya')
                                    ->columnSpanFull(),
                                Select::make('purchase_order_id')
                                    ->visible(fn($record) => $record !== null)
                                    ->label('Purchase Order')
                                    ->required()
                                    ->placeholder('Pilih Purchase Order')
                                    ->options(fn(Get $get): Collection => Purchase_order::query()
                                        ->where('bppb_id', $record->id)
                                        ->get()
                                        ->mapWithKeys(function ($po) {
                                            return [$po->id => $po->noPo . ' - ' . $po->vendor->vendorName];
                                        }))
                                    ->columnSpanFull()
                                    ->createOptionForm([
                                        Hidden::make('bppb_id')
                                            ->default(fn() => $record ? $record->id : null),
                                        Hidden::make('user_id')
                                            ->default(auth()->id()),
                                        TextInput::make('noPo')
                                            ->label('No. PO')
                                            ->required()
                                            ->columnSpanFull(),
                                        Select::make('vendor_id')
                                            ->label('Vendor')
                                            ->placeholder('Pilih Vendor')
                                            ->options(Vendor::all()->pluck('vendorName', 'id'))
                                            ->required()
                                            ->searchable(),
                                    ])
                                    ->createOptionUsing(function (array $data) {
                                        return Purchase_order::create($data)->id;
                                    })
                                    ->searchable(),
                            ])
                            ->default([])
                            ->columns(2)
                            ->maxItems(15)
                            ->createItemButtonLabel('Tambah Tinta'),
                    ]),

                Section::make('Daftar Software')
                    ->columnSpanFull()
                    ->collapsed()
                    ->schema([
                        Repeater::make('bppb_software')
                            ->relationship('bppb_software')
                            ->label('')
                            ->helperText('Silakan isi daftar Software yang akan diajukan')
                            ->schema([
                                Select::make('software_id')
                                    ->label('Nama Software')
                                    ->placeholder('Pilih Nama Software...')
                                    ->searchable()
                                    ->live()
                                    ->getSearchResultsUsing(function (string $search): array {
                                        return Software::query()
                                            ->where('name', 'like', "%{$search}%")
                                            ->limit(20)
                                            ->pluck('name', 'id')
                                            ->toArray();
                                    })
                                    ->getOptionLabelUsing(function ($value): ?string {
                                        return Software::find($value)?->name;
                                    })
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->required(),
                                TextInput::make('qty')
                                    ->label('Qty')
                                    ->required()
                                    ->numeric(),
                                Textarea::make('description')
                                    ->label('Keterangan')
                                    ->helperText('Opsional, bisa diisi dengan spesifikasi software atau catatan lainnya')
                                    ->columnSpanFull(),
                                Select::make('purchase_order_id')
                                    ->visible(fn($record) => $record !== null)
                                    ->label('Purchase Order')
                                    ->required()
                                    ->placeholder('Pilih Purchase Order')
                                    ->options(fn(Get $get): Collection => Purchase_order::query()
                                        ->where('bppb_id', $record->id)
                                        ->get()
                                        ->mapWithKeys(function ($po) {
                                            return [$po->id => $po->noPo . ' - ' . $po->vendor->vendorName];
                                        }))
                                    ->columnSpanFull()
                                    ->createOptionForm([
                                        Hidden::make('bppb_id')
                                            ->default(fn() => $record ? $record->id : null),
                                        Hidden::make('user_id')
                                            ->default(auth()->id()),
                                        TextInput::make('noPo')
                                            ->label('No. PO')
                                            ->required()
                                            ->columnSpanFull(),
                                        Select::make('vendor_id')
                                            ->label('Vendor')
                                            ->placeholder('Pilih Vendor')
                                            ->options(Vendor::all()->pluck('vendorName', 'id'))
                                            ->required()
                                            ->searchable(),
                                    ])
                                    ->createOptionUsing(function (array $data) {
                                        return Purchase_order::create($data)->id;
                                    })
                                    ->searchable(),
                            ])
                            ->default([])
                            ->columns(2)
                            ->maxItems(15)
                            ->createItemButtonLabel('Tambah Software'),
                    ])
            ]);
    }
}
