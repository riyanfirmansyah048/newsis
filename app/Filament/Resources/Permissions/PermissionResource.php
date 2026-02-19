<?php

namespace App\Filament\Resources\Permissions;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Spatie\Permission\Models\Permission;
use App\Filament\Resources\Permissions\Pages\EditPermission;
use App\Filament\Resources\Permissions\Pages\ListPermissions;
use App\Filament\Resources\Permissions\Pages\CreatePermission;
use App\Filament\Resources\Permissions\Schemas\PermissionForm;
use App\Filament\Resources\Permissions\Tables\PermissionsTable;
use Illuminate\Database\Eloquent\Model;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-key';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return 'Akses';
    }

    public static function form(Schema $schema): Schema
    {
        return PermissionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PermissionsTable::configure($table);
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
            'index' => ListPermissions::route('/'),
            'create' => CreatePermission::route('/create'),
            'edit' => EditPermission::route('/{record}/edit'),
        ];
    }
    // CRUD role--------------------------------------------------------------
    // access role
    public static function canAccess(): bool
    {
        return auth()->user()->can('access-permission');
    }
    // create role
    public static function canCreate(): bool
    {
        return auth()->user()->can('create-permission');
    }
    // view/detail role
    public static function canView(Model $record): bool
    {
        return auth()->user()->can('read-permission', $record);
    }
    // edit role
    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('update-permission', $record);
    }
    // delete role
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete-permission', $record);
    }
}
