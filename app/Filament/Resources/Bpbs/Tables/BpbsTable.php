<?php

namespace App\Filament\Resources\Bpbs\Tables;

use App\Models\Bpb;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Tables\Enums\RecordActionsPosition;

class BpbsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                auth()->user()->hasRole('admin') // Periksa apakah user memiliki role "admin"
                    ? Bpb::query() // Jika admin, tampilkan semua data
                    : Bpb::query()->where('user_id', auth()->id()) // Jika bukan admin, hanya tampilkan miliknya sendiri
            )
            ->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->columns([
                TextColumn::make('noBpb')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dateBpb')
                    ->dateTime()
                    ->date('d F Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('dateBpb', 'desc')
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn() => auth()->user()->hasRole('admin')),
                Action::make('print')
                    ->label('Print BPB')
                    ->icon('heroicon-m-printer')
                    ->color('info')
                    ->url(
                        fn($record) =>
                        route('bpb.print', ['id' => $record->id])
                    ),
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
