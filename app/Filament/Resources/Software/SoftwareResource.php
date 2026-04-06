<?php

namespace App\Filament\Resources\Software;

use BackedEnum;
use App\Models\Software;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Software\Pages\EditSoftware;
use App\Filament\Resources\Software\Pages\ViewSoftware;
use App\Filament\Resources\Software\Pages\ListSoftware;
use App\Filament\Resources\Software\Pages\CreateSoftware;
use App\Filament\Resources\Software\Schemas\SoftwareForm;
use App\Filament\Resources\Software\Tables\SoftwareTable;

class SoftwareResource extends Resource
{
    protected static ?string $model = Software::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $modelLabel = 'Data Software';
    protected static ?string $navigationLabel = 'List Software';
    protected static ?string $pluralModelLabel = 'List Software';

    protected static ?int $navigationSort = 6;
    public static function getNavigationGroup(): ?string
    {
        return 'Data Barang';
    }

    public static function form(Schema $schema): Schema
    {
        return SoftwareForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SoftwareTable::configure($table);
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
            'index' => ListSoftware::route('/'),
            'view' => ViewSoftware::route('/{record}'),
            'create' => CreateSoftware::route('/create'),
            'edit' => EditSoftware::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->when(request()->has('brand_software_id'), function (Builder $query) {
                $query->where('brand_software_id', request('brand_software_id'));
            });
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
