<?php

namespace App\Filament\Resources\BrandSoftware;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\Brand_software;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BrandSoftware\Pages\EditBrandSoftware;
use App\Filament\Resources\BrandSoftware\Pages\ListBrandSoftware;
use App\Filament\Resources\BrandSoftware\Pages\CreateBrandSoftware;
use App\Filament\Resources\BrandSoftware\Schemas\BrandSoftwareForm;
use App\Filament\Resources\BrandSoftware\Tables\BrandSoftwareTable;

class BrandSoftwareResource extends Resource
{
    protected static ?string $model = Brand_software::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $modelLabel = 'Data Merek Software';
    protected static ?string $navigationLabel = 'List Merek Software';
    protected static ?string $pluralModelLabel = 'List Merek Software';

    protected static ?int $navigationSort = 5;
    public static function getNavigationGroup(): ?string
    {
        return 'Data Barang';
    }

    public static function form(Schema $schema): Schema
    {
        return BrandSoftwareForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BrandSoftwareTable::configure($table);
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
            'index' => ListBrandSoftware::route('/'),
            'create' => CreateBrandSoftware::route('/create'),
            'edit' => EditBrandSoftware::route('/{record}/edit'),
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
            ->when(request()->has('category_software_id'), function (Builder $query) {
                $query->where('category_software_id', request('category_software_id'));
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
