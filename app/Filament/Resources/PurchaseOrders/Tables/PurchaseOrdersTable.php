<?php

namespace App\Filament\Resources\PurchaseOrders\Tables;

use App\Models\Bpb;
use App\Models\Bppb_ink;
use App\Models\Bppb_item;
use App\Models\Bppb_software;
use Filament\Tables\Table;
use App\Models\Purchase_order;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Tables\Enums\RecordActionsPosition;

class PurchaseOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->query(
                auth()->user()->hasRole('admin')
                    ? Purchase_order::query()
                    : Purchase_order::query()->where('user_id', auth()->id())
            )
            ->columns([
                TextColumn::make('noPo')
                    ->label('No. PO')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('vendor.vendorName')
                    ->label('Vendor')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('datePo')
                    ->label('Tanggal PO')
                    ->date('d F Y')
                    ->sortable()
                    ->searchable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()
                        ->visible(fn(Purchase_order $record) => auth()->user()->hasRole('admin') && $record->bpb()->count() === 0),
                    Action::make('delete')
                        ->label('Delete')
                        ->color('danger')
                        ->icon('heroicon-m-trash')
                        ->visible(fn(Purchase_order $record) => auth()->user()->hasRole('admin') && $record->bpb()->count() === 0)
                        ->schema([
                            Textarea::make('reason')
                                ->label('Alasan Delete')
                                ->required()
                                ->rows(4)
                                ->placeholder('Masukkan alasan kenapa Purchase Order dihapus'),
                        ])
                        ->action(function (array $data, Purchase_order $record) {
                            $reason = trim((string) ($data['reason'] ?? ''));

                            activity()
                                ->performedOn($record)
                                ->causedBy(auth()->user())
                                ->withProperties([
                                    'reason' => $reason,
                                    'noPo' => $record->noPo,
                                ])
                                ->log("deleted: {$reason}");

                            $bpb = Bpb::where('po_id', $record->id)->get();
                            foreach ($bpb as $b) {
                                $b->delete();
                            }

                            Bppb_item::where('purchase_order_id', $record->id)->update(['purchase_order_id' => null]);
                            Bppb_ink::where('purchase_order_id', $record->id)->update(['purchase_order_id' => null]);
                            Bppb_software::where('purchase_order_id', $record->id)->update(['purchase_order_id' => null]);

                            $record->delete();

                            Notification::make()
                                ->title('Purchase Order berhasil dihapus')
                                ->success()
                                ->send();
                        }),
                ]),
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
