<?php

namespace App\Filament\Resources\PurchaseOrders;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\Purchase_order;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PurchaseOrders\Pages\EditPurchaseOrder;
use App\Filament\Resources\PurchaseOrders\Pages\ViewPurchaseOrder;
use App\Filament\Resources\PurchaseOrders\Pages\ListPurchaseOrders;
use App\Filament\Resources\PurchaseOrders\Pages\CreatePurchaseOrder;
use App\Filament\Resources\PurchaseOrders\Schemas\PurchaseOrderForm;
use App\Filament\Resources\PurchaseOrders\Tables\PurchaseOrdersTable;

class PurchaseOrderResource extends Resource
{
    protected static ?string $model = Purchase_order::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $recordTitleAttribute = 'noPo';
    protected static ?string $modelLabel = 'Purchase Order';
    protected static ?string $navigationLabel = 'Purchase Orders';
    protected static ?string $pluralModelLabel = 'Purchase Orders';

    protected static ?int $navigationSort = 2;
    public static function getNavigationGroup(): ?string
    {
        return 'Transaksi';
    }

    public static function form(Schema $schema): Schema
    {
        return PurchaseOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PurchaseOrdersTable::configure($table);
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
            'index' => ListPurchaseOrders::route('/'),
            'create' => CreatePurchaseOrder::route('/create'),
            'view' => ViewPurchaseOrder::route('/{record}'),
            'edit' => EditPurchaseOrder::route('/{record}/edit'),
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
        return auth()->user()->can('access-po');
    }
    public static function canCreate(): bool
    {
        return auth()->user()->can('create-po');
    }
    public static function canView(Model $record): bool
    {
        return auth()->user()->can('read-po', $record);
    }
    public static function canEdit(Model $record): bool
    {
        return auth()->user()->hasRole('admin') || (auth()->user()->can('update-po', $record) && $record->status_id == 3);
    }
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete-po', $record);
    }
}
