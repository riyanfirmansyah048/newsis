<?php

namespace App\Filament\Resources\Bpbs;

use BackedEnum;
use App\Models\Bpb;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Bpbs\Pages\EditBpb;
use App\Filament\Resources\Bpbs\Pages\ListBpbs;
use App\Filament\Resources\Bpbs\Pages\CreateBpb;
use App\Filament\Resources\Bpbs\Schemas\BpbForm;
use App\Filament\Resources\Bpbs\Tables\BpbsTable;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BpbResource extends Resource
{
    protected static ?string $model = Bpb::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $recordTitleAttribute = 'noBpb';
    protected static ?string $modelLabel = 'BPB';
    protected static ?string $navigationLabel = 'BPB';
    protected static ?string $pluralModelLabel = 'BPB';

    protected static ?int $navigationSort = 3;
    public static function getNavigationGroup(): ?string
    {
        return 'Transaksi';
    }

    public static function form(Schema $schema): Schema
    {
        return BpbForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BpbsTable::configure($table);
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
            'index' => ListBpbs::route('/'),
            'create' => CreateBpb::route('/create'),
            'edit' => EditBpb::route('/{record}/edit'),
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
        return auth()->user()->can('access-bpb');
    }
    public static function canCreate(): bool
    {
        return auth()->user()->can('create-bpb');
    }
    public static function canView(Model $record): bool
    {
        return auth()->user()->can('read-bpb', $record);
    }
    public static function canEdit(Model $record): bool
    {
        return auth()->user()->hasRole('admin') || (auth()->user()->can('update-bpb', $record) && $record->status_id == 3);
    }
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete-bpb', $record);
    }
}
