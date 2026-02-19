<?php

namespace App\Filament\Pages\Auth;

use App\Models\Company;
use App\Models\Position;
use App\Models\Regional;
use App\Models\Department;
use App\Models\BusinessUnit;
use App\Models\Section as SectionModel;
use Filament\Schemas\Schema;
use App\Models\SubDepartment;
// use Filament\Auth\Pages\Register;
use Caresome\FilamentAuthDesigner\Pages\Auth\Register;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class RegisterCustom extends Register
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                /* =======================
                 * PERSONAL INFORMATION
                 * ======================= */
                Section::make('Personal Information')
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        $this->getEmailFormComponent()
                            ->autofocus()
                            ->columnSpanFull(),

                        TextInput::make('username')
                            ->label('Username')
                            ->required()
                            ->unique('users', 'username')
                            ->columnSpanFull(),

                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('NIK')
                            ->label('NIK')
                            ->required()
                            ->unique('users', 'NIK'),

                        Select::make('gender')
                            ->label('Jenis Kelamin')
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
                            ->columnSpanFull(),
                    ]),

                /* =======================
                 * COMPANY INFORMATION
                 * ======================= */
                Section::make('Company Information')
                    ->columns(1)
                    ->collapsible()
                    ->schema([
                        Select::make('idCompany')
                            ->label('Perusahaan')
                            ->options(
                                Company::query()->pluck('companyName', 'id')
                            )
                            ->live()
                            ->searchable()
                            ->afterStateUpdated(fn(Set $set) => $this->resetCompanyHierarchy($set))
                            ->required(),

                        Select::make('idRegional')
                            ->label('Region / Lokasi')
                            ->options(
                                fn(Get $get): Collection =>
                                Regional::query()
                                    ->where('idCompany', $get('idCompany'))
                                    ->pluck('regionalName', 'id')
                            )
                            ->live()
                            ->searchable()
                            ->afterStateUpdated(fn(Set $set) => $this->resetAfterRegional($set))
                            ->required(),

                        Select::make('idBusinessUnit')
                            ->label('Bisnis Unit')
                            ->options(
                                fn(Get $get): Collection =>
                                BusinessUnit::query()
                                    ->where('idRegional', $get('idRegional'))
                                    ->pluck('businessUnitName', 'id')
                            )
                            ->live()
                            ->searchable()
                            ->afterStateUpdated(fn(Set $set) => $this->resetAfterBusinessUnit($set))
                            ->required(),

                        Select::make('idDepartment')
                            ->label('Departemen')
                            ->options(
                                fn(Get $get): Collection =>
                                Department::query()
                                    ->where('idBusinessUnit', $get('idBusinessUnit'))
                                    ->pluck('departmentName', 'id')
                            )
                            ->live()
                            ->searchable()
                            ->afterStateUpdated(fn(Set $set) => $this->resetAfterDepartment($set))
                            ->required(),

                        Select::make('idSubDepartment')
                            ->label('Sub Departemen')
                            ->options(
                                fn(Get $get): Collection =>
                                SubDepartment::query()
                                    ->where('idDepartment', $get('idDepartment'))
                                    ->pluck('subDepartmentName', 'id')
                            )
                            ->live()
                            ->searchable()
                            ->afterStateUpdated(fn(Set $set) => $set('idSection', null)),

                        Select::make('idSection')
                            ->label('Section / Bagian')
                            ->options(
                                fn(Get $get): Collection =>
                                SectionModel::query()
                                    ->where('idSubDepartment', $get('idSubDepartment'))
                                    ->pluck('sectionName', 'id')
                            )
                            ->live()
                            ->searchable(),

                        Select::make('idPosition')
                            ->label('Jabatan')
                            ->options(
                                Position::query()->pluck('positionName', 'id')
                            )
                            ->required()
                            ->searchable(),
                    ]),

                /* =======================
                 * PASSWORD
                 * ======================= */
                Section::make('Password')
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        $this->getPasswordFormComponent()->columnSpanFull(),
                        $this->getPasswordConfirmationFormComponent()->columnSpanFull(),
                    ]),
            ]);
    }

    /* =======================
     * RESET HELPERS
     * ======================= */
    protected function resetCompanyHierarchy(Set $set): void
    {
        $set('idRegional', null);
        $set('idBusinessUnit', null);
        $set('idDepartment', null);
        $set('idSubDepartment', null);
        $set('idSection', null);
    }

    protected function resetAfterRegional(Set $set): void
    {
        $set('idBusinessUnit', null);
        $set('idDepartment', null);
        $set('idSubDepartment', null);
        $set('idSection', null);
    }

    protected function resetAfterBusinessUnit(Set $set): void
    {
        $set('idDepartment', null);
        $set('idSubDepartment', null);
        $set('idSection', null);
    }

    protected function resetAfterDepartment(Set $set): void
    {
        $set('idSubDepartment', null);
        $set('idSection', null);
    }
}
