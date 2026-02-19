<?php

namespace App\Filament\Resources\Expeditions;

use BackedEnum;
use App\Models\Expedition;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Expeditions\Pages\EditExpedition;
use App\Filament\Resources\Expeditions\Pages\ListExpeditions;
use App\Filament\Resources\Expeditions\Pages\CreateExpedition;
use App\Filament\Resources\Expeditions\Schemas\ExpeditionForm;
use App\Filament\Resources\Expeditions\Tables\ExpeditionsTable;

class ExpeditionResource extends Resource
{
    protected static ?string $model = Expedition::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'noExpedition';
    protected static ?string $modelLabel = 'Expedisi';
    protected static ?string $navigationLabel = 'Expedisi';
    protected static ?string $pluralModelLabel = 'Expedisi';

    protected static ?int $navigationSort = 5;
    public static function getNavigationGroup(): ?string
    {
        return 'Transaksi';
    }

    public static function form(Schema $schema): Schema
    {
        return ExpeditionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExpeditionsTable::configure($table);
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
            'index' => ListExpeditions::route('/'),
            'create' => CreateExpedition::route('/create'),
            'edit' => EditExpedition::route('/{record}/edit'),
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
        return auth()->user()->can('access-expedition');
    }
    public static function canCreate(): bool
    {
        return auth()->user()->can('create-expedition');
    }
    public static function canView(Model $record): bool
    {
        return auth()->user()->can('read-expedition', $record);
    }
    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('update-expedition', $record);
    }
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete-expedition', $record);
    }
}
