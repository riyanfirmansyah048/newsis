<?php

namespace App\Filament\Resources\Items;

use BackedEnum;
use App\Models\Item;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Items\Pages\EditItem;
use App\Filament\Resources\Items\Pages\ListItems;
use App\Filament\Resources\Items\Pages\CreateItem;
use App\Filament\Resources\Items\Schemas\ItemForm;
use App\Filament\Resources\Items\Tables\ItemsTable;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $modelLabel = 'Data Barang';
    protected static ?string $navigationLabel = 'List Barang';
    protected static ?string $pluralModelLabel = 'List Barang';

    protected static ?int $navigationSort = 3;
    public static function getNavigationGroup(): ?string
    {
        return 'Data Barang';
    }

    public static function form(Schema $schema): Schema
    {
        return ItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ItemsTable::configure($table);
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
            'index' => ListItems::route('/'),
            'create' => CreateItem::route('/create'),
            'edit' => EditItem::route('/{record}/edit'),
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
            ->when(request()->has('brand_id'), function (Builder $query) {
                $query->where('brand_id', request('brand_id'));
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
