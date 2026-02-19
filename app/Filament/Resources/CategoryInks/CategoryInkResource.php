<?php

namespace App\Filament\Resources\CategoryInks;

use BackedEnum;
use Filament\Tables\Table;
use App\Models\Category_ink;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CategoryInks\Pages\EditCategoryInk;
use App\Filament\Resources\CategoryInks\Pages\ListCategoryInks;
use App\Filament\Resources\CategoryInks\Pages\CreateCategoryInk;
use App\Filament\Resources\CategoryInks\Schemas\CategoryInkForm;
use App\Filament\Resources\CategoryInks\Tables\CategoryInksTable;

class CategoryInkResource extends Resource
{
    protected static ?string $model = Category_ink::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-star';

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 7;
    protected static ?string $modelLabel = 'Data Kategori Tinta';
    protected static ?string $navigationLabel = 'List Kategori Tinta';
    protected static ?string $pluralModelLabel = 'List Kategori Tinta';

    public static function getNavigationGroup(): ?string
    {
        return 'Data Barang';
    }

    public static function form(Schema $schema): Schema
    {
        return CategoryInkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoryInksTable::configure($table);
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
            'index' => ListCategoryInks::route('/'),
            'create' => CreateCategoryInk::route('/create'),
            'edit' => EditCategoryInk::route('/{record}/edit'),
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
