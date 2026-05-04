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

    public static function getNavigationBadge(): ?string
    {
        if (! auth()->user()?->can('update-service')) {
            return null;
        }

        $count = Service::query()
            ->activeForPic((int) auth()->id())
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        if (! auth()->user()?->can('update-service')) {
            return null;
        }

        return 'danger';
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

    public static function hasValidProfileContact(): bool
    {
        $email = trim((string) auth()->user()?->email);
        $ext = trim((string) auth()->user()?->ext);
        $departmentId = auth()->user()?->idDepartment;

        $hasValidEmail = filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
        $hasValidExt = $ext !== '' && ! in_array($ext, ['0', '-'], true);
        $hasDepartment = filled($departmentId);

        return $hasValidEmail && $hasValidExt && $hasDepartment;
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create-service')
            && static::hasValidProfileContact();
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
