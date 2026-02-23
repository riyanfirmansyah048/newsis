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
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class EmailForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('idUser')
                    ->options(
                        fn(): Collection => User::query()
                            ->get()
                            ->mapWithKeys(fn($user) => [$user->id => "{$user->NIK} - {$user->name}"])
                    )
                    ->label('Nama Karyawan')
                    ->placeholder('Pilih Karyawan')
                    ->preload()
                    ->searchable()
                    ->columnSpanFull()
                    ->default(auth()->id())
                    ->required()
                    ->reactive()
                    ->unique(
                        ignoreRecord: true,
                        modifyRuleUsing: fn($rule) =>
                        $rule->whereNull('deleted_at')
                    )
                    ->afterStateUpdated(function (Set $set, $state) {
                        $user = User::find($state);
                        if ($user) {
                            $set('idUser', $user->id);
                            $set('idCompany', $user->idCompany);
                            $set('emailName', $user->username);
                        }
                    })
                    ->disabled(fn() => !auth()->user()->hasRole('admin')),

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
                    ->disabled()
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
