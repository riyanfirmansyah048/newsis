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
                    ->copyable(),
                TextColumn::make('bppb.noBppb')
                    ->label("No. Bppb")
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                TextColumn::make('bppb.purchase_orders.noPo')
                    ->label("No. PO")
                    ->searchable()
                    ->copyable()
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
                TextColumn::make('print_count')
                    ->label('Print Count')
                    ->badge()
                    ->color('info')
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
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->schema(fn(Expedition $record) => $record->print_count > 0 ? [
                        Textarea::make('reason')
                            ->label('Alasan Print Ulang')
                            ->required()
                            ->rows(4)
                            ->placeholder('Masukkan alasan kenapa dokumen ini diprint ulang'),
                    ] : [])
                    ->action(function (array $data, Expedition $record) {
                        $params = [];

                        if ($record->print_count > 0) {
                            $params['reason'] = trim((string) ($data['reason'] ?? ''));
                        }

                        return redirect()->to(route('expedition.print', ['id' => $record->id] + $params));
                    })
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
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
