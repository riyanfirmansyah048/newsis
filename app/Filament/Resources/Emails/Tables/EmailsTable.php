<?php

namespace App\Filament\Resources\Emails\Tables;

use App\Models\Email;
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

class EmailsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->query(
                auth()->user()->hasRole('admin') // Periksa apakah user memiliki role "admin"
                    ? Email::query() // Jika admin, tampilkan semua data
                    : Email::query()->where('idUser', auth()->id()) // Jika bukan admin, hanya tampilkan miliknya sendiri
            )
            ->columns([
                TextColumn::make('activeStatus')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => $state ? 'Active' : 'Not Active')
                    ->icon(fn($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn($state) => $state ? 'success' : 'danger'),
                TextColumn::make('user.NIK')
                    ->label('NIK Karyawan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Nama Karyawan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('emailName')
                    ->label('Nama Email')
                    ->searchable(),
                TextColumn::make('domainEmail.domainName')
                    ->label('Nama Domain')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('company.companyName')
                    ->label('Nama Perusahaan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Email')
                    ->date('d F Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
                Action::make('Print')
                    ->url(fn(Email $record) => route('permohonanemail.print', $record->id))
                    // ->openUrlInNewTab()
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->tooltip('Cetak permohonan email')
                    ->visible(fn(Email $record) => $record->activeStatus == 0),
                Action::make('Print')
                    ->url(fn(Email $record) => route('konfigurasiemail.print', $record->id))
                    // ->openUrlInNewTab()
                    ->icon('heroicon-o-printer')
                    ->color('warning')
                    ->tooltip('Cetak konfigurasi email')
                    ->visible(fn(Email $record) => $record->activeStatus == 1),
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
