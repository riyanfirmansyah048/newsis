<?php

namespace App\Filament\Resources\Bppbs\Tables;

use App\Models\Bppb;
use Filament\Tables\Table;
use App\Models\Bppb_status;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Tables\Enums\RecordActionsPosition;

class BppbsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            // ->query(
            //     auth()->user()->hasRole('admin') // Periksa apakah user memiliki role "admin"
            //         ? Bppb::query()->whereIn('bppb_type_id', [1, 2, 3, 4]) // Jika admin, tampilkan hanya data dengan bppb_type_id = 1
            //         : Bppb::query()->where('user_id', auth()->id())->whereIn('bppb_type_id', [1, 3]) // Jika bukan admin, hanya tampilkan miliknya sendiri dengan bppb_type_id = 1
            // )
            ->query(function () {

                $query = auth()->user()->hasRole('admin')
                    ? Bppb::query()->whereIn('bppb_type_id', [1, 2, 3, 4])
                    : Bppb::query()->where('user_id', auth()->id())->whereIn('bppb_type_id', [1, 3]);

                if (request()->filled('status_id')) {
                    $query->where('status_id', request('status_id'));
                }

                return $query;
            })
            ->columns([
                TextColumn::make('noBppb')
                    ->label('No. BPPB')
                    ->searchable(),
                TextColumn::make('status.name')
                    ->label('Status')
                    ->tooltip(fn($record) => $record->status->description)
                    ->badge()
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
                TextColumn::make('bppb_type.name')
                    ->label('Type BPPB')
                    ->badge()
                    ->color(fn($record) => match ($record->bppb_type_id) {
                        1 => 'primary',
                        2 => 'warning',
                        3 => 'success',
                        4 => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('flow_label')
                    ->label('Alur BPPB')
                    ->badge()
                    ->color(fn(Bppb $record) => $record->isSoftwareConsolidation() ? 'warning' : 'gray'),
                TextColumn::make('user.NIK')
                    ->label('NIK Karyawan')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Nama Karyawan')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Tanggal BPPB')
                    ->date('d F Y')
                    ->sortable(),
                TextColumn::make('print_count')
                    ->label('Print Count')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                TextColumn::make('status.name')
                    ->label('Status')
                    ->tooltip(fn($record) => $record->status->description)
                    ->badge()
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
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('flow_type')
                    ->label('Alur BPPB')
                    ->options([
                        'regular' => 'BPPB Biasa',
                        'software_consolidation' => 'BPPB Konsolidasi Software',
                    ])
                    ->query(function ($query, array $data) {
                        return match ($data['value'] ?? null) {
                            'software_consolidation' => $query->whereHas('bppb_software', function ($softwareQuery) {
                                $softwareQuery
                                    ->whereNotNull('noBppbPemohon')
                                    ->where('noBppbPemohon', '!=', '');
                            }),
                            'regular' => $query->whereDoesntHave('bppb_software', function ($softwareQuery) {
                                $softwareQuery
                                    ->whereNotNull('noBppbPemohon')
                                    ->where('noBppbPemohon', '!=', '');
                            }),
                            default => $query,
                        };
                    }),
                SelectFilter::make('bppb_type_id')
                    ->label('Type BPPB')
                    ->relationship('bppb_type', 'name'),
                SelectFilter::make('status')
                    ->relationship('status', 'name')
                    ->multiple(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()
                        ->visible(fn($record) => auth()->user()->hasRole('admin') || in_array($record->status_id, [1, 2, 3])),
                    DeleteAction::make()
                        ->visible(fn($record) => auth()->user()->hasRole('admin') || in_array($record->status_id, [1, 2, 3])),
                ]),
                Action::make('Print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn(Bppb $record) => route('bppb.print', $record->id))
                    // ->openUrlInNewTab()
                    ->visible(
                        fn(Bppb $record) =>
                        auth()->user()?->hasRole('admin')
                            || in_array($record->status_id, [1, 2, 3])
                    ),
                Action::make('finish')
                    ->label('Selesai')
                    ->icon('heroicon-o-check-badge')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->visible(
                        fn(Bppb $record) =>
                        in_array($record->status_id, [5, 7]) &&
                            (auth()->user()?->hasRole('admin') || $record->user_id === auth()->id())
                    )
                    ->action(function (Bppb $record) {
                        $record->update([
                            'status_id' => 6,
                        ]);

                        Notification::make()
                            ->title('BPPB berhasil diselesaikan')
                            ->success()
                            ->send();
                    }),
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
