<?php

namespace App\Filament\Resources\CategorySoftware;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use App\Models\Category_software;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CategorySoftware\Pages\EditCategorySoftware;
use App\Filament\Resources\CategorySoftware\Pages\ListCategorySoftware;
use App\Filament\Resources\CategorySoftware\Pages\CreateCategorySoftware;
use App\Filament\Resources\CategorySoftware\Schemas\CategorySoftwareForm;
use App\Filament\Resources\CategorySoftware\Tables\CategorySoftwareTable;

class CategorySoftwareResource extends Resource
{
    protected static ?string $model = Category_software::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $modelLabel = 'Data Kategori Software';
    protected static ?string $navigationLabel = 'List Kategori Software';
    protected static ?string $pluralModelLabel = 'List Kategori Software';

    protected static ?int $navigationSort = 4;
    public static function getNavigationGroup(): ?string
    {
        return 'Data Barang';
    }

    public static function form(Schema $schema): Schema
    {
        return CategorySoftwareForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategorySoftwareTable::configure($table);
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
            'index' => ListCategorySoftware::route('/'),
            'create' => CreateCategorySoftware::route('/create'),
            'edit' => EditCategorySoftware::route('/{record}/edit'),
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
