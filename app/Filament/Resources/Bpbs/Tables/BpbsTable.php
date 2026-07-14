<?php

namespace App\Filament\Resources\Bpbs\Tables;

use App\Models\Bpb;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Actions\ActionGroup;

class BpbsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                auth()->user()->hasRole('admin')
                    ? Bpb::query()->withTrashed()
                    : Bpb::query()->withTrashed()->where('user_id', auth()->id())
            )
            ->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->columns([
                TextColumn::make('noBpb')
                    ->label('No. BPB')
                    ->color(fn(Bpb $record) => $record->trashed() ? 'danger' : null)
                    ->sortable()
                    ->copyable()
                    ->searchable(),
                TextColumn::make('purchase_order.bppb.noBppb')
                    ->label('No. BPPB')
                    ->sortable()
                    ->copyable()
                    ->searchable(),
                TextColumn::make('purchase_order.noPo')
                    ->label("No. PO")
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->sortable(),
                TextColumn::make('purchase_order.vendor.vendorName')
                    ->label('Vendor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('dateBpb')
                    ->dateTime()
                    ->date('d F Y H:i')
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
            ->defaultSort('dateBpb', 'desc')
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->visible(fn($record) => auth()->user()->hasRole('admin') && ! $record->trashed()),
                    Action::make('print')
                        ->label('Print BPB')
                        ->icon('heroicon-m-printer')
                        ->color('info')
                        ->schema(fn(Bpb $record) => $record->print_count > 0 ? [
                            Textarea::make('reason')
                                ->label('Alasan Print Ulang')
                                ->required()
                                ->rows(4)
                                ->placeholder('Masukkan alasan kenapa dokumen ini diprint ulang'),
                        ] : [])
                        ->action(function (array $data, Bpb $record) {
                            $params = [];

                            if ($record->print_count > 0) {
                                $params['reason'] = trim((string) ($data['reason'] ?? ''));
                            }

                            return redirect()->to(route('bpb.print', ['id' => $record->id] + $params));
                        })
                        ->visible(fn($record) => auth()->user()->hasRole('admin') && ! $record->trashed()),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
