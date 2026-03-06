<?php

namespace App\Filament\Resources\Internets\Tables;

use App\Models\Internet;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Tables\Enums\RecordActionsPosition;

class InternetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->query(
                auth()->user()->hasRole('admin') // Periksa apakah user memiliki role "admin"
                    ? Internet::query() // Jika admin, tampilkan semua data
                    : Internet::query()->where('idUser', auth()->id()) // Jika bukan admin, hanya tampilkan miliknya sendiri
            )
            ->columns([
                IconColumn::make('activeStatus')
                    ->boolean(),
                TextColumn::make('user.NIK')
                    ->label('NIK Karyawan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Nama Karyawan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('ip')
                    ->searchable(),
                IconColumn::make('activeStatus')
                    ->boolean(),
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
                        ->visible(fn(Internet $record) => auth()->user()?->can('update-internet', $record)),
                    DeleteAction::make()
                        ->visible(fn(Internet $record) => auth()->user()?->can('delete-internet', $record)),
                ]),
                Action::make('Print')
                    ->url(fn(Internet $record) => route('internet.print', $record->id))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->tooltip('Cetak permohonan internet')
                    ->visible(fn(Internet $record) => $record->activeStatus == 0),
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
