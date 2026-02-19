<?php

namespace App\Filament\Resources\Services\Tables;

use App\Models\Service;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Tables\Enums\RecordActionsPosition;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->query(
                auth()->user()->hasRole('admin') // Periksa apakah user memiliki role "admin"
                    ? Service::query() // Jika admin, tampilkan semua data
                    : Service::query()->where('user_id', auth()->id()) // Jika bukan admin, hanya tampilkan miliknya sendiri
            )
            ->columns([
                TextColumn::make('noService')
                    ->searchable(),
                TextColumn::make('user.NIK')
                    ->label('NIK Karyawan')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Nama Karyawan')
                    ->sortable(),
                TextColumn::make('icUser.name')
                    ->label('IC yang mengerjakan')
                    ->sortable(),
                TextColumn::make('item.name')
                    ->label('Nama Barang')
                    ->sortable(),
                TextColumn::make('status.name')
                    ->label('Status')
                    ->sortable()
                    ->color(fn($record) => match ($record->status_id) {
                        1 => 'warning',
                        2 => 'danger',
                        3 => 'primary',
                        4 => 'success',
                        5 => 'gray',
                        6 => 'info',
                        7 => 'gray',
                        default => 'default',
                    }),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()
                        ->visible(fn($record) => auth()->user()->hasRole('admin')),
                    DeleteAction::make()
                        ->visible(fn($record) => auth()->user()->hasRole('admin')),
                ]),
                Action::make('Print')
                    ->url(fn(Service $record) => route('service.print', $record->id))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-printer')
                    ->color('success'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
