<?php

namespace App\Filament\Resources\Reminders;

use App\Filament\Resources\Reminders\Pages\CreateReminder;
use App\Filament\Resources\Reminders\Pages\EditReminder;
use App\Filament\Resources\Reminders\Pages\ListReminders;
use App\Filament\Resources\Reminders\Schemas\ReminderForm;
use App\Filament\Resources\Reminders\Tables\RemindersTable;
use App\Models\Reminder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ReminderResource extends Resource
{
    protected static ?string $model = Reminder::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-bell-alert';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $modelLabel = 'Reminder';
    protected static ?string $navigationLabel = 'Reminder';
    protected static ?string $pluralModelLabel = 'Reminder';
    protected static ?int $navigationSort = 6;

    public static function getNavigationGroup(): ?string
    {
        return 'Transaksi';
    }

    public static function form(Schema $schema): Schema
    {
        return ReminderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RemindersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReminders::route('/'),
            'create' => CreateReminder::route('/create'),
            'edit' => EditReminder::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->can('access-reminder');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create-reminder');
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()->can('read-reminder');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('update-reminder');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete-reminder');
    }
}
