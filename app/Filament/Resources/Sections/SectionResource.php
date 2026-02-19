<?php

namespace App\Filament\Resources\Sections;

use BackedEnum;
use App\Models\Section;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Sections\Pages\EditSection;
use App\Filament\Resources\Sections\Pages\ListSections;
use App\Filament\Resources\Sections\Pages\CreateSection;
use App\Filament\Resources\Sections\Schemas\SectionForm;
use App\Filament\Resources\Sections\Tables\SectionsTable;

class SectionResource extends Resource
{
    protected static ?string $model = Section::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $recordTitleAttribute = 'sectionName';

    protected static ?string $modelLabel = 'Section/Bagian';
    protected static ?string $navigationLabel = 'List Section/Bagian';
    protected static ?string $pluralModelLabel = 'List Section/Bagian';

    protected static ?int $navigationSort = 7;

    public static function getNavigationGroup(): ?string
    {
        return 'Data Karyawan';
    }

    public static function form(Schema $schema): Schema
    {
        return SectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SectionsTable::configure($table);
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
            'index' => ListSections::route('/'),
            'create' => CreateSection::route('/create'),
            'edit' => EditSection::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(
                request()->filled('subdepartment_id'),
                fn(Builder $query) =>
                $query->where('idSubDepartment', request('subdepartment_id'))
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
