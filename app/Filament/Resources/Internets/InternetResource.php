<?php

namespace App\Filament\Resources\Internets;

use BackedEnum;
use App\Models\Internet;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Internets\Pages\EditInternet;
use App\Filament\Resources\Internets\Pages\ListInternets;
use App\Filament\Resources\Internets\Pages\CreateInternet;
use App\Filament\Resources\Internets\Schemas\InternetForm;
use App\Filament\Resources\Internets\Tables\InternetsTable;

class InternetResource extends Resource
{
    protected static ?string $model = Internet::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cloud';

    protected static ?string $recordTitleAttribute = 'description';

    protected static ?string $modelLabel = 'Data Internet';
    protected static ?string $navigationLabel = 'List Internet';
    protected static ?string $pluralModelLabel = 'List Internet';

    protected static ?int $navigationSort = 3;
    public static function getNavigationGroup(): ?string
    {
        return 'Domain, Email & Internet';
    }

    public static function form(Schema $schema): Schema
    {
        return InternetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InternetsTable::configure($table);
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
            'index' => ListInternets::route('/'),
            'create' => CreateInternet::route('/create'),
            'edit' => EditInternet::route('/{record}/edit'),
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
        return auth()->user()->can('access-internet');
    }
    public static function canCreate(): bool
    {
        return auth()->user()->can('create-internet');
    }
    public static function canView(Model $record): bool
    {
        return auth()->user()->can('read-internet', $record);
    }
    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('update-internet', $record);
    }
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete-internet', $record);
    }
}
