<?php

namespace App\Filament\Resources\Emails\Schemas;

use App\Models\User;
use App\Models\Company;
use App\Models\DomainEmail;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class EmailForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('idUser')
                    ->relationship(
                        name: 'user',
                        titleAttribute: 'name'
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn(User $record) => "{$record->NIK} - {$record->name}"
                    )
                    ->searchable()
                    ->searchDebounce(500)
                    ->label('Nama Karyawan')
                    ->placeholder('Cari Karyawan...')
                    ->default(auth()->id())
                    ->required()
                    ->reactive()
                    ->rules(function ($record) {
                        if (auth()->user()?->hasRole('admin')) {
                            return [];
                        }

                        $rule = Rule::unique('emails', 'idUser');
                        if ($record) {
                            $rule->ignore($record->id);
                        }

                        return [$rule];
                    })
                    ->validationMessages([
                        'unique' => 'User ini sudah memiliki email',
                    ])
                    ->disabled(fn() => !auth()->user()->hasRole('admin'))
                    ->columnSpanFull(),

                Select::make('idCompany')
                    ->options(
                        fn(): Collection => Company::query()
                            ->get()
                            ->mapWithKeys(fn($company) => [$company->id => $company->companyName])
                    )
                    ->label('Nama Perusahaan')
                    ->placeholder('Pilih Perusahaan')
                    ->preload()
                    ->searchable()
                    ->default(fn() => auth()->user()->idCompany)
                    ->afterStateUpdated(function (Set $set) {
                        $set('idDomainEmail', null);
                    })
                    ->dehydrated(true)
                    ->disabled(fn() => !auth()->user()->hasRole('admin'))
                    ->required(),

                // Hidden::make('idCompany')
                //     ->default(fn() => auth()->user()->idCompany)
                //     ->required(),

                // Hidden::make('idUser')
                //     ->default(fn() => auth()->id())
                //     ->unique(ignoreRecord: true)
                //     ->required(),

                Select::make('idDomainEmail')
                    ->label('Nama Domain')
                    ->placeholder('Pilih Domain')
                    ->options(
                        fn(Get $get): Collection => DomainEmail::query()
                            ->where('idCompany', $get('idCompany'))
                            ->get()
                            ->pluck('domainName', 'id')
                    )
                    ->visible(fn() => auth()->user()->hasRole('admin')),

                TextInput::make('emailName')
                    ->label('Nama Email (info : nama emailnya saja tanpa nama domain)')
                    ->required()
                    ->maxLength(255)
                    ->default(fn() => auth()->user()->username),
                TextInput::make('passwordEmail')
                    ->label('Password Email')
                    ->maxLength(255)
                    ->visible(fn() => auth()->user()->hasRole('admin')),
                Toggle::make('activeStatus')
                    ->label('Is Active')
                    ->reactive()
                    ->visible(fn() => auth()->user()->hasRole('admin'))
                    ->afterStateUpdated(function (Set $set, $state) {
                        if ($state) {
                            $set('activeDate', now());
                        } else {
                            $set('activeDate', null);
                        }
                    }),
                Hidden::make('activeDate')
                    ->default(null),
            ]);
    }
}
