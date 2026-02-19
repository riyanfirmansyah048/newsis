<?php

namespace App\Filament\Resources\BusinessUnits;

use BackedEnum;
use Filament\Tables\Table;
use App\Models\BusinessUnit;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\BusinessUnits\Pages\EditBusinessUnit;
use App\Filament\Resources\BusinessUnits\Pages\ListBusinessUnits;
use App\Filament\Resources\BusinessUnits\Pages\CreateBusinessUnit;
use App\Filament\Resources\BusinessUnits\Schemas\BusinessUnitForm;
use App\Filament\Resources\BusinessUnits\Tables\BusinessUnitsTable;

class BusinessUnitResource extends Resource
{
    protected static ?string $model = BusinessUnit::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $recordTitleAttribute = 'businessUnitName';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Bisnis Unit';
    protected static ?string $navigationLabel = 'List Bisnis Unit';
    protected static ?string $pluralModelLabel = 'List Bisnis Unit';

    public static function getNavigationGroup(): ?string
    {
        return 'Data Karyawan';
    }

    public static function form(Schema $schema): Schema
    {
        return BusinessUnitForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BusinessUnitsTable::configure($table);
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
            'index' => ListBusinessUnits::route('/'),
            'create' => CreateBusinessUnit::route('/create'),
            'edit' => EditBusinessUnit::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(
                request()->filled('regional_id'),
                fn(Builder $query) =>
                $query->where('idRegional', request('regional_id'))
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
