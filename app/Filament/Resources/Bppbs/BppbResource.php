<?php

namespace App\Filament\Resources\Bppbs;

use BackedEnum;
use App\Models\Bppb;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Bppbs\Pages\EditBppb;
use App\Filament\Resources\Bppbs\Pages\ListBppbs;
use App\Filament\Resources\Bppbs\Pages\CreateBppb;
use App\Filament\Resources\Bppbs\Schemas\BppbForm;
use App\Filament\Resources\Bppbs\Tables\BppbsTable;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Bppbs\Pages\EditBppbCustom;

class BppbResource extends Resource
{
    protected static ?string $model = Bppb::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $recordTitleAttribute = 'noBppb';

    protected static ?string $modelLabel = 'BPPB';
    protected static ?string $navigationLabel = 'BPPB';
    protected static ?string $pluralModelLabel = 'BPPB';

    protected static ?int $navigationSort = 1;
    public static function getNavigationGroup(): ?string
    {
        return 'Transaksi';
    }

    public static function form(Schema $schema): Schema
    {
        $user = auth()->user();

        if (!$user->department || !$user->department->code) {
            Notification::make()
                ->title('Profil Belum Lengkap')
                ->body('Lengkapi terlebih dahulu data profil Anda sebelum mengajukan BPPB.')
                ->danger()
                ->send();

            // Bisa redirect atau menampilkan placeholder kosong
            return $schema->schema([]);
        }

        return BppbForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BppbsTable::configure($table);
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
            'index' => ListBppbs::route('/'),
            'create' => CreateBppb::route('/create'),
            // 'edit' => EditBppb::route('/{record}/edit'),
            'edit' => EditBppbCustom::route('/{record}/edit'),
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
        // return auth()->user()->can('access-bppb');
        return auth()->user()->idCompany !== null &&
            auth()->user()->idRegional !== null &&
            auth()->user()->idBusinessUnit !== null &&
            auth()->user()->idDepartment !== null &&
            auth()->user()->can('access-bppb');
    }
    public static function canCreate(): bool
    {
        return auth()->user()->can('create-bppb');
    }
    public static function canView(Model $record): bool
    {
        return auth()->user()->can('read-bppb', $record);
    }
    public static function canEdit(Model $record): bool
    {
        return auth()->user()->hasRole('admin') || (auth()->user()->can('update-bppb', $record) && $record->status_id == 3);
    }
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete-bppb', $record);
    }
}
