<?php

namespace App\Filament\Resources\Regionals;

use BackedEnum;
use App\Models\Regional;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Regionals\Pages\EditRegional;
use App\Filament\Resources\Regionals\Pages\ListRegionals;
use App\Filament\Resources\Regionals\Pages\CreateRegional;
use App\Filament\Resources\Regionals\Schemas\RegionalForm;
use App\Filament\Resources\Regionals\Tables\RegionalsTable;

class RegionalResource extends Resource
{
    protected static ?string $model = Regional::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $recordTitleAttribute = 'regionalName';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Regional/Lokasi';
    protected static ?string $navigationLabel = 'List Regional/Lokasi';
    protected static ?string $pluralModelLabel = 'List Regional/Lokasi';

    public static function getNavigationGroup(): ?string
    {
        return 'Data Karyawan';
    }

    public static function form(Schema $schema): Schema
    {
        return RegionalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RegionalsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRegionals::route('/'),
            'create' => CreateRegional::route('/create'),
            'edit' => EditRegional::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(
                request()->filled('company_id'),
                fn(Builder $query) =>
                $query->where('idCompany', request('company_id'))
            );
    }

    // CRUD data--------------------------------------------------------------
    public static function canAccess(): bool
    {
        return auth()->user()->can('access-data');
    }
    public static function canCreate(): bool
    {
        return auth()->user()->can('create-data');
    }
    public static function canView(Model $record): bool
    {
        return auth()->user()->can('read-data', $record);
    }
    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('update-data', $record);
    }
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete-data', $record);
    }
}
