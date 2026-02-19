<?php

namespace App\Filament\Resources\DomainEmails;

use BackedEnum;
use Filament\Tables\Table;
use App\Models\DomainEmail;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DomainEmails\Pages\EditDomainEmail;
use App\Filament\Resources\DomainEmails\Pages\ListDomainEmails;
use App\Filament\Resources\DomainEmails\Pages\CreateDomainEmail;
use App\Filament\Resources\DomainEmails\Schemas\DomainEmailForm;
use App\Filament\Resources\DomainEmails\Tables\DomainEmailsTable;

class DomainEmailResource extends Resource
{
    protected static ?string $model = DomainEmail::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $recordTitleAttribute = 'domainName';
    protected static ?string $modelLabel = 'Data Domain';
    protected static ?string $navigationLabel = 'List Domain';
    protected static ?string $pluralModelLabel = 'List Domain';

    protected static ?int $navigationSort = 1;
    public static function getNavigationGroup(): ?string
    {
        return 'Domain, Email & Internet';
    }

    public static function form(Schema $schema): Schema
    {
        return DomainEmailForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DomainEmailsTable::configure($table);
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
            'index' => ListDomainEmails::route('/'),
            'create' => CreateDomainEmail::route('/create'),
            'edit' => EditDomainEmail::route('/{record}/edit'),
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
        return auth()->user()->can('access-domainemail');
    }
    public static function canCreate(): bool
    {
        return auth()->user()->can('create-domainemail');
    }
    public static function canView(Model $record): bool
    {
        return auth()->user()->can('read-domainemail', $record);
    }
    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('update-domainemail', $record);
    }
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete-domainemail', $record);
    }
}
