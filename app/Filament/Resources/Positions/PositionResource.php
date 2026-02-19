<?php

namespace App\Filament\Resources\Positions;

use BackedEnum;
use App\Models\Position;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\Positions\Pages\EditPosition;
use App\Filament\Resources\Positions\Pages\ListPositions;
use App\Filament\Resources\Positions\Pages\CreatePosition;
use App\Filament\Resources\Positions\Schemas\PositionForm;
use App\Filament\Resources\Positions\Tables\PositionsTable;

class PositionResource extends Resource
{
    protected static ?string $model = Position::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-identification';

    protected static ?string $recordTitleAttribute = 'positionName';

    protected static ?int $navigationSort = 8;

    protected static ?string $modelLabel = 'Jabatan';
    protected static ?string $navigationLabel = 'List Jabatan';
    protected static ?string $pluralModelLabel = 'List Jabatan';

    public static function getNavigationGroup(): ?string
    {
        return 'Data Karyawan';
    }

    public static function form(Schema $schema): Schema
    {
        return PositionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PositionsTable::configure($table);
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
            'index' => ListPositions::route('/'),
            'create' => CreatePosition::route('/create'),
            'edit' => EditPosition::route('/{record}/edit'),
        ];
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
