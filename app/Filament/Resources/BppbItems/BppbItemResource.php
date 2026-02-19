<?php

namespace App\Filament\Resources\BppbItems;

use App\Filament\Resources\BppbItems\Pages\CreateBppbItem;
use App\Filament\Resources\BppbItems\Pages\EditBppbItem;
use App\Filament\Resources\BppbItems\Pages\ListBppbItems;
use App\Filament\Resources\BppbItems\Schemas\BppbItemForm;
use App\Filament\Resources\BppbItems\Tables\BppbItemsTable;
use App\Models\Bppb_item;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BppbItemResource extends Resource
{
    protected static ?string $model = Bppb_item::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    // protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $modelLabel = 'BPPB Item';
    protected static ?string $navigationLabel = 'BPPB Item';
    protected static ?string $pluralModelLabel = 'BPPB Item';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return BppbItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BppbItemsTable::configure($table);
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
            'index' => ListBppbItems::route('/'),
            'create' => CreateBppbItem::route('/create'),
            'edit' => EditBppbItem::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
