<?php

namespace App\Filament\Resources\BppbSoftware;

use App\Filament\Resources\BppbSoftware\Pages\CreateBppbSoftware;
use App\Filament\Resources\BppbSoftware\Pages\EditBppbSoftware;
use App\Filament\Resources\BppbSoftware\Pages\ListBppbSoftware;
use App\Filament\Resources\BppbSoftware\Schemas\BppbSoftwareForm;
use App\Filament\Resources\BppbSoftware\Tables\BppbSoftwareTable;
use App\Models\Bppb_software;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BppbSoftwareResource extends Resource
{
    protected static ?string $model = Bppb_software::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    // protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $modelLabel = 'Software';
    protected static ?string $navigationLabel = 'Management Software';
    protected static ?string $pluralModelLabel = 'Software';
    // protected static bool $shouldRegisterNavigation = true;

    protected static ?int $navigationSort = 7;

    public static function getNavigationGroup(): ?string
    {
        return 'Transaksi';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return BppbSoftwareForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BppbSoftwareTable::configure($table);
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
            'index' => ListBppbSoftware::route('/'),
            'create' => CreateBppbSoftware::route('/create'),
            'edit' => EditBppbSoftware::route('/{record}/edit'),
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
    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('admin');
    }
}
