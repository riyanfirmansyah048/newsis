<?php

namespace App\Filament\Resources\Services;

use BackedEnum;
use App\Models\Service;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Services\Pages\EditService;
use App\Filament\Resources\Services\Pages\ListServices;
use App\Filament\Resources\Services\Pages\CreateService;
use App\Filament\Resources\Services\Schemas\ServiceForm;
use App\Filament\Resources\Services\Tables\ServicesTable;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $recordTitleAttribute = 'noService';
    protected static ?string $modelLabel = 'Service / Memo IT';
    protected static ?string $navigationLabel = 'Service / Memo IT';
    protected static ?string $pluralModelLabel = 'Service / Memo IT';

    protected static ?int $navigationSort = 4;
    public static function getNavigationGroup(): ?string
    {
        return 'Transaksi';
    }

    public static function form(Schema $schema): Schema
    {
        return ServiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServicesTable::configure($table);
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
            'index' => ListServices::route('/'),
            'create' => CreateService::route('/create'),
            'edit' => EditService::route('/{record}/edit'),
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
        return auth()->user()->can('access-service');
    }
    public static function canCreate(): bool
    {
        return auth()->user()->can('create-service');
    }
    public static function canView(Model $record): bool
    {
        return auth()->user()->can('read-service', $record);
    }
    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('update-service', $record);
    }
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete-service', $record);
    }
}
