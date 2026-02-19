<?php

namespace App\Filament\Resources\ProductForms;

use BackedEnum;
use Filament\Tables\Table;
use App\Models\ProductForm;
use App\Models\Product_form;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductForms\Pages\EditProductForm;
use App\Filament\Resources\ProductForms\Pages\ListProductForms;
use App\Filament\Resources\ProductForms\Pages\CreateProductForm;
use App\Filament\Resources\ProductForms\Schemas\ProductFormForm;
use App\Filament\Resources\ProductForms\Tables\ProductFormsTable;

class ProductFormResource extends Resource
{
    protected static ?string $model = Product_form::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'Data Bentuk';
    protected static ?string $navigationLabel = 'List Bentuk';
    protected static ?string $pluralModelLabel = 'List Bentuk';

    public static function getNavigationGroup(): ?string
    {
        return 'Data Barang';
    }

    public static function form(Schema $schema): Schema
    {
        return ProductFormForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductFormsTable::configure($table);
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
            'index' => ListProductForms::route('/'),
            'create' => CreateProductForm::route('/create'),
            'edit' => EditProductForm::route('/{record}/edit'),
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
