<?php

namespace App\Filament\Resources\Emails;

use BackedEnum;
use App\Models\Email;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Emails\Pages\EditEmail;
use App\Filament\Resources\Emails\Pages\ListEmails;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Emails\Pages\CreateEmail;
use App\Filament\Resources\Emails\Schemas\EmailForm;
use App\Filament\Resources\Emails\Tables\EmailsTable;

class EmailResource extends Resource
{
    protected static ?string $model = Email::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $recordTitleAttribute = 'emailName';

    protected static ?string $modelLabel = 'Data Email';
    protected static ?string $navigationLabel = 'List Email';
    protected static ?string $pluralModelLabel = 'List Email';

    protected static ?int $navigationSort = 2;
    public static function getNavigationGroup(): ?string
    {
        return 'Domain, Email & Internet';
    }

    public static function form(Schema $schema): Schema
    {
        return EmailForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmailsTable::configure($table);
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
            'index' => ListEmails::route('/'),
            'create' => CreateEmail::route('/create'),
            'edit' => EditEmail::route('/{record}/edit'),
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
        return auth()->user()->can('access-email');
    }
    public static function canCreate(): bool
    {
        return auth()->user()->can('create-email');
    }
    public static function canView(Model $record): bool
    {
        return auth()->user()->can('read-email', $record);
    }
    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('update-email', $record);
    }
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete-email', $record);
    }
}
