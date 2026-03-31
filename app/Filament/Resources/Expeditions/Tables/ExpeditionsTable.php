<?php

namespace App\Filament\Resources\Expeditions\Tables;

use App\Models\Expedition;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Enums\RecordActionsPosition;

class ExpeditionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                Expedition::query()->withTrashed()
            )
            ->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->columns([
                TextColumn::make('noExpedition')
                    ->label("No. Expedisi")
                    ->searchable()
                    ->sortable()
                    ->color(fn(Expedition $record) => $record->trashed() ? 'danger' : null)
                    ->copyable()
                    ->copyMessage('No. Expedisi berhasil disalin')
                    ->copyMessageDuration(1500),
                TextColumn::make('bppb.noBppb')
                    ->label("No. Bppb")
                    ->searchable()
                    ->sortable(),
                TextColumn::make('bppb.purchase_orders.noPo')
                    ->label("No. PO")
                    ->searchable()
                    ->sortable(),
                TextColumn::make('expeditor')
                    ->label("pengirim")
                    ->searchable(),
                TextColumn::make('bppb.user.name')
                    ->label("Penerima")
                    ->searchable(),
                TextColumn::make('dateStart')
                    ->label("Tgl Eks")
                    ->date('d F Y')
                    ->sortable(),
                TextColumn::make('datePrint')
                    ->label("Tgl Print")
                    ->date('d F Y')
                    ->sortable(),
                TextColumn::make('deleted_at')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state ? 'Cancelled' : 'Active')
                    ->color(fn($state) => $state ? 'danger' : 'success')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('Print')
                    ->label('Print')
                    ->url(fn(Expedition $record) => route('expedition.print', $record->id))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->visible(fn(Expedition $record) => ! $record->trashed()),
                Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->visible(fn(Expedition $record) => ! $record->trashed())
                    ->schema([
                        Textarea::make('reason')
                            ->label('Alasan Cancel')
                            ->required()
                            ->rows(4)
                            ->placeholder('Masukkan alasan kenapa expedisi di-cancel'),
                    ])
                    ->action(function (array $data, Expedition $record) {
                        $reason = trim($data['reason']);

                        activity()
                            ->performedOn($record)
                            ->causedBy(auth()->user())
                            ->withProperties([
                                'reason' => $reason,
                                'noExpedition' => $record->noExpedition,
                            ])
                            ->log("cancelled: {$reason}");

                        activity()->withoutLogs(function () use ($record) {
                            $record->delete();
                        });
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    // ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
