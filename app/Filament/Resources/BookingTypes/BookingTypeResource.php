<?php

namespace App\Filament\Resources\BookingTypes;

use BackedEnum;
use App\Models\BookingType;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BookingTypes\Pages\EditBookingType;
use App\Filament\Resources\BookingTypes\Pages\ListBookingTypes;
use App\Filament\Resources\BookingTypes\Pages\CreateBookingType;
use App\Filament\Resources\BookingTypes\Schemas\BookingTypeForm;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\BookingTypes\Tables\BookingTypesTable;

class BookingTypeResource extends Resource
{
    protected static ?string $model = BookingType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $modelLabel = 'Jenis Booking';
    protected static ?string $navigationLabel = 'Jenis Booking';
    protected static ?string $pluralModelLabel = 'Jenis Booking';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return 'Booking';
    }

    public static function form(Schema $schema): Schema
    {
        return BookingTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BookingTypesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBookingTypes::route('/'),
            'create' => CreateBookingType::route('/create'),
            'edit' => EditBookingType::route('/{record}/edit'),
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
        return auth()->user()->can('access-booking-type');
    }
    public static function canCreate(): bool
    {
        return auth()->user()->can('create-booking-type');
    }
    public static function canView(Model $record): bool
    {
        return auth()->user()->can('read-booking-type', $record);
    }
    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('update-booking-type', $record);
    }
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete-booking-type', $record);
    }
}
