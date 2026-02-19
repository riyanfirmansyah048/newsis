<?php

namespace App\Filament\Resources\Users;

use BackedEnum;
use App\Models\User;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'List Karyawan';

    public static function getNavigationGroup(): ?string
    {
        return 'Data Karyawan';
    }

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    // CRUD Permisions--------------------------------------------------------------
    public static function canAccess(): bool
    {
        return auth()->user()->can('access-user');
    }
    public static function canCreate(): bool
    {
        return auth()->user()->can('create-user');
    }
    public static function canView(Model $record): bool
    {
        return auth()->user()->can('read-user', $record);
    }
    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('update-user', $record);
    }
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete-user', $record);
    }
}
