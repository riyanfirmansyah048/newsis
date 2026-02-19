<?php

namespace App\Filament\Resources\SubDepartments;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\SubDepartment;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SubDepartments\Pages\EditSubDepartment;
use App\Filament\Resources\SubDepartments\Pages\ListSubDepartments;
use App\Filament\Resources\SubDepartments\Pages\CreateSubDepartment;
use App\Filament\Resources\SubDepartments\Schemas\SubDepartmentForm;
use App\Filament\Resources\SubDepartments\Tables\SubDepartmentsTable;

class SubDepartmentResource extends Resource
{
    protected static ?string $model = SubDepartment::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'subDepartmentName';

    public static function getNavigationGroup(): ?string
    {
        return 'Data Karyawan';
    }

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return SubDepartmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SubDepartmentsTable::configure($table);
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
            'index' => ListSubDepartments::route('/'),
            'create' => CreateSubDepartment::route('/create'),
            'edit' => EditSubDepartment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(
                request()->filled('department_id'),
                fn(Builder $query) =>
                $query->where('idDepartment', request('department_id'))
            );
    }

    // CRUD data--------------------------------------------------------------
    public static function canAccess(): bool
    {
        return auth()->user()->can('access-data');
    }
    public static function canCreate(): bool
    {
        return auth()->user()->can('create-data');
    }
    public static function canView(Model $record): bool
    {
        return auth()->user()->can('read-data', $record);
    }
    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('update-data', $record);
    }
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete-data', $record);
    }
}
