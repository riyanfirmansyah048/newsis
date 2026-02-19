<?php

namespace App\Filament\Resources\BrandInks;

use BackedEnum;
use App\Models\Brand_ink;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BrandInks\Pages\EditBrandInk;
use App\Filament\Resources\BrandInks\Pages\ListBrandInks;
use App\Filament\Resources\BrandInks\Pages\CreateBrandInk;
use App\Filament\Resources\BrandInks\Schemas\BrandInkForm;
use App\Filament\Resources\BrandInks\Tables\BrandInksTable;

class BrandInkResource extends Resource
{
    protected static ?string $model = Brand_ink::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-star';

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 8;
    protected static ?string $modelLabel = 'Data Merek Tinta';
    protected static ?string $navigationLabel = 'List Merek Tinta';
    protected static ?string $pluralModelLabel = 'List Merek Tinta';

    public static function getNavigationGroup(): ?string
    {
        return 'Data Barang';
    }

    public static function form(Schema $schema): Schema
    {
        return BrandInkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BrandInksTable::configure($table);
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
            'index' => ListBrandInks::route('/'),
            'create' => CreateBrandInk::route('/create'),
            'edit' => EditBrandInk::route('/{record}/edit'),
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
            ->when(request()->has('category_ink_id'), function (Builder $query) {
                $query->where('category_ink_id', request('category_ink_id'));
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
