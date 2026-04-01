<?php

namespace App\Filament\Resources\BookingOrders;

use BackedEnum;
use App\Models\BookingOrder;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BookingOrders\Pages\EditBookingOrder;
use App\Filament\Resources\BookingOrders\Pages\ListBookingOrders;
use App\Filament\Resources\BookingOrders\Pages\CreateBookingOrder;
use App\Filament\Resources\BookingOrders\Schemas\BookingOrderForm;
use App\Filament\Resources\BookingOrders\Tables\BookingOrdersTable;
use Illuminate\Database\Eloquent\Model;

class BookingOrderResource extends Resource
{
    protected static ?string $model = BookingOrder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $recordTitleAttribute = 'topic';
    protected static ?string $modelLabel = 'Booking Order';
    protected static ?string $navigationLabel = 'Booking Orders';
    protected static ?string $pluralModelLabel = 'Booking Orders';

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return 'Booking';
    }

    public static function form(Schema $schema): Schema
    {
        return BookingOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BookingOrdersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBookingOrders::route('/'),
            'create' => CreateBookingOrder::route('/create'),
            'edit' => EditBookingOrder::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function getNavigationBadge(): ?string
    {
        if (! auth()->user()?->hasRole('admin')) {
            return null;
        }

        $count = static::getModel()::query()
            ->where('status', 'pending')
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        if (! auth()->user()?->hasRole('admin')) {
            return null;
        }

        return 'danger';
    }

    // CRUD data--------------------------------------------------------------
    public static function canAccess(): bool
    {
        return auth()->user()->can('access-booking-order');
    }
    public static function canCreate(): bool
    {
        return auth()->user()->can('create-booking-order');
    }
    public static function canView(Model $record): bool
    {
        return auth()->user()->can('read-booking-order', $record);
    }
    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('update-booking-order', $record);
    }
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete-booking-order', $record);
    }
}
