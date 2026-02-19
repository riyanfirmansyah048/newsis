<?php

namespace App\Filament\Resources\Mutations;

use BackedEnum;
use App\Models\User;
use App\Models\Mutation;
use Filament\Tables\Table;
use App\Models\Assets_item;
use Filament\Schemas\Schema;
use App\Models\Mutation_item;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\Mutations\Pages\EditMutation;
use App\Filament\Resources\Mutations\Pages\ListMutations;
use App\Filament\Resources\Mutations\Pages\CreateMutation;
use App\Filament\Resources\Mutations\Schemas\MutationForm;
use App\Filament\Resources\Mutations\Tables\MutationsTable;

class MutationResource extends Resource
{
    protected array $selectedAssets = [];
    protected static ?string $model = Mutation::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-truck';

    protected static ?string $modelLabel = 'Mutasi';
    protected static ?string $navigationLabel = 'Mutasi';
    protected static ?string $pluralModelLabel = 'Mutasi';

    protected static ?int $navigationSort = 6;

    public static function getNavigationGroup(): ?string
    {
        return 'Transaksi';
    }

    public static function form(Schema $schema): Schema
    {
        return MutationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MutationsTable::configure($table);
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
            'index' => ListMutations::route('/'),
            'create' => CreateMutation::route('/create'),
            'edit' => EditMutation::route('/{record}/edit'),
        ];
    }

    // CRUD data--------------------------------------------------------------
    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('admin');
    }
}
