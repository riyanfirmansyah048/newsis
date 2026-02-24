<?php

namespace App\Filament\Pages;

use App\Models\Company;
use App\Models\Section;
use App\Models\Position;
use App\Models\Regional;
use App\Models\Department;
use Filament\Tables\Table;
use App\Models\BusinessUnit;
use Filament\Schemas\Schema;
use App\Models\SubDepartment;
use Illuminate\Support\Collection;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Schemas\Components\Section as FormSection;
use Livewire\Form;

class EditProfileCustom extends BaseEditProfile
{
    /**
     * Redirect ke halaman login setelah profile disimpan
     */
    protected function afterSave(): void
    {
        $this->redirectRoute('filament.sis.auth.login');
    }

    /**
     * Form schema (Filament v4)
     */
    public function form(Schema $schema): Schema
    {
        return $schema->components([
            // ======================
            // DATA AKUN
            // ======================
            FormSection::make('Profile Information')
                ->collapsed(false)
                ->schema([
                    TextInput::make('email')
                        ->label('Email')
                        ->required()
                        ->unique(ignoreRecord: true),

                    TextInput::make('username')
                        ->label('Username')
                        ->required()
                        ->unique(ignoreRecord: true),

                    TextInput::make('NIK')
                        ->label('NIK')
                        ->required()
                        ->unique(ignoreRecord: true),

                    TextInput::make('name')
                        ->label('Nama Lengkap')
                        ->required()
                        ->autofocus(),

                    Select::make('gender')
                        ->label('Jenis Kelamin')
                        ->placeholder('Pilih jenis kelamin')
                        ->options([
                            '1' => 'Laki-laki',
                            '2' => 'Perempuan',
                        ])
                        ->required(),

                    TextInput::make('hp')
                        ->label('Handphone'),

                    TextInput::make('ext')
                        ->label('Ext')
                        ->required(),

                    Textarea::make('address')
                        ->label('Alamat')
                        ->required(),

                    // ======================
                    // STRUKTUR ORGANISASI
                    // ======================
                    Select::make('idCompany')
                        ->label('Perusahaan')
                        ->placeholder('Pilih Perusahaan')
                        ->options(
                            Company::query()
                                ->pluck('companyName', 'id')
                        )
                        ->live()
                        ->afterStateUpdated(function (Set $set) {
                            $set('idRegional', null);
                            $set('idBusinessUnit', null);
                            $set('idDepartment', null);
                            $set('idSubDepartment', null);
                            $set('idSection', null);
                        })
                        ->searchable()
                        ->required(),

                    Select::make('idRegional')
                        ->label('Region / Lokasi')
                        ->placeholder('Pilih Region/Lokasi')
                        ->options(
                            fn(Get $get): Collection =>
                            Regional::query()
                                ->where('idCompany', $get('idCompany'))
                                ->pluck('regionalName', 'id')
                        )
                        ->live()
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
                        ->options(
                            fn(Get $get): Collection =>
                            BusinessUnit::query()
                                ->where('idRegional', $get('idRegional'))
                                ->pluck('businessUnitName', 'id')
                        )
                        ->live()
                        ->afterStateUpdated(function (Set $set) {
                            $set('idDepartment', null);
                            $set('idSubDepartment', null);
                            $set('idSection', null);
                        })
                        ->searchable()
                        ->required(),

                    Select::make('idDepartment')
                        ->label('Departemen')
                        ->placeholder('Pilih Departemen')
                        ->options(
                            fn(Get $get): Collection =>
                            Department::query()
                                ->where('idBusinessUnit', $get('idBusinessUnit'))
                                ->pluck('departmentName', 'id')
                        )
                        ->live()
                        ->afterStateUpdated(function (Set $set) {
                            $set('idSubDepartment', null);
                            $set('idSection', null);
                        })
                        ->searchable()
                        ->required(),

                    Select::make('idSubDepartment')
                        ->label('Sub Departemen')
                        ->placeholder('Pilih Sub Departemen')
                        ->options(
                            fn(Get $get): Collection =>
                            SubDepartment::query()
                                ->where('idDepartment', $get('idDepartment'))
                                ->pluck('subDepartmentName', 'id')
                        )
                        ->live()
                        ->afterStateUpdated(fn(Set $set) => $set('idSection', null))
                        ->searchable()
                        ->required(),

                    Select::make('idSection')
                        ->label('Sub Section / Bagian')
                        ->placeholder('Pilih Section/Bagian')
                        ->options(
                            fn(Get $get): Collection =>
                            Section::query()
                                ->where('idSubDepartment', $get('idSubDepartment'))
                                ->pluck('sectionName', 'id')
                        )
                        ->searchable(),

                    // ======================
                    // JABATAN
                    // ======================
                    Select::make('idPosition')
                        ->label('Jabatan')
                        ->placeholder('Pilih Jabatan')
                        ->options(
                            Position::query()
                                ->pluck('positionName', 'id')
                        )
                        ->searchable()
                        ->required(),

                    // ======================
                    // FOTO PROFIL
                    // ======================

                    // FileUpload::make('image')
                    //     ->label('Foto Profile')
                    //     ->image()
                    //     ->imageEditor()
                    //     ->avatar()
                    //     ->directory('profiles')
                    //     ->visibility('public')
                    //     ->columnSpanFull(),

                    // FileUpload::make('image')
                    //     ->label('Foto Profile')
                    //     ->image()
                    //     ->imageEditor()
                    //     ->avatar()
                    //     ->disk('photo_profiles') // 🔥 disk custom
                    //     ->directory('/')          // langsung ke folder itu
                    //     ->visibility('public')
                    //     ->columnSpanFull(),

                    // FileUpload::make('image')
                    //     ->label('Foto Profile')
                    //     ->image()
                    //     ->avatar()
                    //     ->imageEditor()
                    //     ->disk('public')
                    //     ->directory('photo-profiles')
                    //     ->visibility('public')
                    //     ->maxSize(2048)
                    //     ->columnSpanFull(),

                    // ======================
                    // PASSWORD
                    // ======================
                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->required(),

                    TextInput::make('password_confirmation')
                        ->label('Konfirmasi Password')
                        ->password()
                        ->required(),
                ]),
        ]);
    }
}
