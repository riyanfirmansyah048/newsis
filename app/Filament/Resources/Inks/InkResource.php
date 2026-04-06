<?php

namespace App\Filament\Resources\Inks;

use BackedEnum;
use App\Models\Ink;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Inks\Pages\EditInk;
use App\Filament\Resources\Inks\Pages\ViewInk;
use App\Filament\Resources\Inks\Pages\ListInks;
use App\Filament\Resources\Inks\Pages\CreateInk;
use App\Filament\Resources\Inks\Schemas\InkForm;
use App\Filament\Resources\Inks\Tables\InksTable;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InkResource extends Resource
{
    protected static ?string $model = Ink::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-star';

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 9;
    protected static ?string $modelLabel = 'Data Tinta';
    protected static ?string $navigationLabel = 'List Tinta';
    protected static ?string $pluralModelLabel = 'List Tinta';

    public static function getNavigationGroup(): ?string
    {
        return 'Data Barang';
    }

    public static function form(Schema $schema): Schema
    {
        return InkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InksTable::configure($table);
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
            'index' => ListInks::route('/'),
            'create' => CreateInk::route('/create'),
            'view' => ViewInk::route('/{record}/view'),
            'edit' => EditInk::route('/{record}/edit'),
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
            ->when(request()->has('brand_ink_id'), function (Builder $query) {
                $query->where('brand_ink_id', request('brand_ink_id'));
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
