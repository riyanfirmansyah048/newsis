<?php

namespace App\Filament\Resources\BppbInks;

use App\Filament\Resources\BppbInks\Pages\CreateBppbInk;
use App\Filament\Resources\BppbInks\Pages\EditBppbInk;
use App\Filament\Resources\BppbInks\Pages\ListBppbInks;
use App\Filament\Resources\BppbInks\Schemas\BppbInkForm;
use App\Filament\Resources\BppbInks\Tables\BppbInksTable;
use App\Models\Bppb_ink;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BppbInkResource extends Resource
{
    protected static ?string $model = Bppb_ink::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    // protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $modelLabel = 'BPPB Tinta';
    protected static ?string $navigationLabel = 'BPPB Tinta';
    protected static ?string $pluralModelLabel = 'BPPB Tinta';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return BppbInkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BppbInksTable::configure($table);
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
            'index' => ListBppbInks::route('/'),
            'create' => CreateBppbInk::route('/create'),
            'edit' => EditBppbInk::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
