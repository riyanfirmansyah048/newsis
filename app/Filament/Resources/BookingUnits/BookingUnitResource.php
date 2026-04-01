<?php

namespace App\Filament\Resources\BookingUnits;

use BackedEnum;
use App\Models\BookingUnit;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BookingUnits\Pages\EditBookingUnit;
use App\Filament\Resources\BookingUnits\Pages\ListBookingUnits;
use App\Filament\Resources\BookingUnits\Pages\CreateBookingUnit;
use App\Filament\Resources\BookingUnits\Schemas\BookingUnitForm;
use App\Filament\Resources\BookingUnits\Tables\BookingUnitsTable;
use Illuminate\Database\Eloquent\Model;

class BookingUnitResource extends Resource
{
    protected static ?string $model = BookingUnit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $modelLabel = 'Booking Unit';
    protected static ?string $navigationLabel = 'Booking Units';
    protected static ?string $pluralModelLabel = 'Booking Units';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return 'Booking';
    }

    public static function form(Schema $schema): Schema
    {
        return BookingUnitForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BookingUnitsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBookingUnits::route('/'),
            'create' => CreateBookingUnit::route('/create'),
            'edit' => EditBookingUnit::route('/{record}/edit'),
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
        return auth()->user()->can('access-booking-unit');
    }
    public static function canCreate(): bool
    {
        return auth()->user()->can('create-booking-unit');
    }
    public static function canView(Model $record): bool
    {
        return auth()->user()->can('read-booking-unit', $record);
    }
    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('update-booking-unit', $record);
    }
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete-booking-unit', $record);
    }
}
