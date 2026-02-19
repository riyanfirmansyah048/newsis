<?php

namespace App\Filament\Resources\Units;

use BackedEnum;
use App\Models\Unit;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Units\Pages\EditUnit;
use App\Filament\Resources\Units\Pages\ListUnits;
use App\Filament\Resources\Units\Pages\CreateUnit;
use App\Filament\Resources\Units\Schemas\UnitForm;
use App\Filament\Resources\Units\Tables\UnitsTable;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $modelLabel = 'Data Unit/Satuan';
    protected static ?string $navigationLabel = 'List Unit';
    protected static ?string $pluralModelLabel = 'List Unit';

    public static function getNavigationGroup(): ?string
    {
        return 'Data Barang';
    }

    public static function form(Schema $schema): Schema
    {
        return UnitForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UnitsTable::configure($table);
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
            'index' => ListUnits::route('/'),
            'create' => CreateUnit::route('/create'),
            'edit' => EditUnit::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    // CRUD data--------------------------------------------------------------
    public static function canAccess(): bool
    {
        return auth()->user()->can('access-item');
    }
    public static function canCreate(): bool
    {
        return auth()->user()->can('create-item');
    }
    public static function canView(Model $record): bool
    {
        return auth()->user()->can('read-item', $record);
    }
    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('update-item', $record);
    }
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete-item', $record);
    }
}
