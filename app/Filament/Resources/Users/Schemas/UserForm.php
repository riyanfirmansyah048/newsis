<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Company;
use App\Models\Section;
use App\Models\Position;
use App\Models\Regional;
use App\Models\Department;
use App\Models\BusinessUnit;
use Filament\Schemas\Schema;
use App\Models\SubDepartment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MultiSelect;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Section as FormSection;


class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FormSection::make('Data Akun')
                    ->collapsed()
                    ->columns(1)
                    ->columnSpanFull()
                    ->schema([
                        // FileUpload::make('image')
                        //     ->label('Foto Profile')
                        //     ->image()
                        //     ->imageEditor()
                        //     ->avatar()
                        //     ->directory('profiles')
                        //     ->visibility('public')
                        //     ->columnSpanFull(),
                        TextInput::make('name')
                            ->required()
                            ->label('Nama Lengkap')
                            ->columnSpanFull()
                            ->autofocus(),
                        TextInput::make('NIK')
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->label('NIK'),
                        TextInput::make('username')
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->label('Username'),
                        TextInput::make('email')
                            ->unique(ignoreRecord: true)
                            ->email(),
                        TextInput::make('password')
                            ->password()
                            ->label('Password')
                            ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                            ->required(fn(string $context) => $context === 'create')
                            ->minLength(8)
                            ->maxLength(32)
                            ->visible(fn(string $context) => $context === 'create' || $context === 'edit')
                            ->dehydrated(fn($state) => filled($state)),
                        MultiSelect::make('roles')
                            ->relationship('roles', 'name')
                            ->label('Roles')
                            ->preload()
                            ->searchable(),
                        MultiSelect::make('permissions')
                            ->relationship('permissions', 'name')
                            ->label('Permissions')
                            ->preload()
                            ->searchable()
                            ->helperText('Permissions can be assigned directly to the user.'),
                        Toggle::make('resign')
                            ->label('Is Resign')
                            ->columnSpanFull()
                            ->reactive(),
                        DatePicker::make('tanggalResign')
                            ->label('Tanggal Resign')
                            ->visible(fn($get) => $get('resign'))
                            ->dehydrateStateUsing(fn($state, $set) => $state ? $state : $set('tanggalResign', null)),
                        //data karyawan___________________________________________________________
                        TextInput::make('ext')
                            ->label('ext')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('address')
                            ->label('Alamat')
                            ->columnSpanFull(),
                        Select::make('idCompany')
                            ->label('Perusahaan')
                            ->placeholder('Pilih Perusahaan')
                            ->options(Company::all()->pluck('companyName', 'id'))
                            ->live()
                            ->columnSpanFull()
                            ->afterStateUpdated(function (Set $set) {
                                $set('idRegional', null);
                                $set('idBusinessUnit', null);
                                $set('idDepartment', null);
                                $set('idSubDepartment', null);
                                $set('idSection', null);
                                // $set('idPosition', null);
                            })
                            ->searchable()
                            ->required(),
                        Select::make('idRegional')
                            ->label('Region/Lokasi')
                            ->placeholder('Pilih Region/Lokasi')
                            // ->options(\App\Models\Regional::all()->pluck('regionalName', 'id'))
                            ->options(fn(Get $get): Collection => Regional::query()
                                ->where('idCompany', $get('idCompany'))
                                ->pluck('regionalName', 'id'))
                            ->live()
                            ->columnSpanFull()
                            ->afterStateUpdated(function (Set $set) {
                                $set('idBusinessUnit', null);
                                $set('idDepartment', null);
                                $set('idSubDepartment', null);
                                $set('idSection', null);
                            })
                            ->searchable()
                            ->required(),
                        Select::make('idBusinessUnit')
                            ->label('Bisnis Unit')
                            ->placeholder('Pilih Bisnis Unit')
                            // ->options(\App\Models\BusinessUnit::all()->pluck('businessUnitName', 'id'))
                            ->options(fn(Get $get): Collection => BusinessUnit::query()
                                ->where('idRegional', $get('idRegional'))
                                ->pluck('businessUnitName', 'id'))
                            ->live()
                            ->columnSpanFull()
                            ->afterStateUpdated(function (Set $set) {
                                $set('idDepartment', null);
                                $set('idSubDepartment', null);
                                $set('idSection', null);
                            })
                            ->searchable()
                            ->required(),
                        Select::make('idDepartment')
                            ->label('Departemen')
                            ->placeholder('Pilih departemen')
                            // ->options(\App\Models\Department::all()->pluck('departmentName', 'id'))
                            ->options(fn(Get $get): Collection => Department::query()
                                ->where('idBusinessUnit', $get('idBusinessUnit'))
                                ->pluck('departmentName', 'id'))
                            ->live()
                            ->columnSpanFull()
                            ->afterStateUpdated(function (Set $set) {
                                $set('idSubDepartment', null);
                                $set('idSection', null);
                            })
                            ->searchable()
                            ->required(),
                        Select::make('idSubDepartment')
                            ->label('Sub Departemen')
                            ->placeholder('Pilih sub departemen')
                            // ->options(\App\Models\SubDepartment::all()->pluck('subDepartmentName', 'id'))
                            ->options(fn(Get $get): Collection => SubDepartment::query()
                                ->where('idDepartment', $get('idDepartment'))
                                ->pluck('subDepartmentName', 'id'))
                            ->live()
                            ->columnSpanFull()
                            ->afterStateUpdated(function (Set $set) {
                                $set('idSection', null);
                            })
                            ->searchable(),
                        Select::make('idSection')
                            ->label('Sub Section/Bagian')
                            ->placeholder('Pilih section/bagian')
                            // ->options(\App\Models\Section::all()->pluck('sectionName', 'id'))
                            ->options(fn(Get $get): Collection => Section::query()
                                ->where('idSubDepartment', $get('idSubDepartment'))
                                ->pluck('sectionName', 'id'))
                            ->live()
                            ->columnSpanFull()
                            ->searchable(),
                        Select::make('idPosition')
                            ->label('Jabatan')
                            ->placeholder('Pilih jabatan')
                            ->options(Position::all()->pluck('positionName', 'id'))
                            ->searchable()
                            ->columnSpanFull()
                            ->required(),
                    ]),
            ]);
    }
}
