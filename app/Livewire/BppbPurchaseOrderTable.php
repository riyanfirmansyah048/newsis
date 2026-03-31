<?php

namespace App\Livewire;

use App\Models\Bpb;
use Livewire\Component;
use App\Models\Bppb_ink;
use App\Models\Bppb_item;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Models\Bppb_software;
use App\Models\Purchase_order;
use Filament\Actions\ActionGroup;
use Illuminate\Contracts\View\View;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class BppbPurchaseOrderTable extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    /** ID BPPB dari Custom Page */
    public int $bppbId;

    protected function getTableQuery(): Builder
    {
        return Purchase_order::query()
            ->where('bppb_id', $this->bppbId);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->columns([
                TextColumn::make('noPo')
                    ->label('No. Purchasing Order')
                    ->searchable(),

                TextColumn::make('vendor.vendorName')
                    ->label('Supplier'),

                TextColumn::make('bpb.noBpb')
                    ->label('No. BPB'),

                TextColumn::make('datePo')
                    ->label('Tanggal PO')
                    ->date('d F Y'),
            ])
            ->defaultSort('datePo', 'desc')
            ->recordActions([
                ActionGroup::make([
                    Action::make('view')
                        ->label('View PO')
                        ->color('primary')
                        ->icon('heroicon-m-eye')
                        ->action(function (Purchase_order $record) {
                            return redirect()->route('filament.sis.resources.purchase-orders.view', $record);
                        }),
                    Action::make('edit')
                        ->label('Edit PO')
                        ->color('warning')
                        ->icon('heroicon-m-pencil-square')
                        ->visible(fn(Purchase_order $record) => auth()->user()->hasRole('admin') && $record->bpb()->count() === 0)
                        ->action(function (Purchase_order $record) {
                            return redirect()->route('filament.sis.resources.purchase-orders.edit', $record);
                        }),
                    Action::make('delete')
                        ->label('Delete PO')
                        ->color('danger')
                        ->icon('heroicon-m-trash')
                        ->requiresConfirmation()
                        ->visible(fn(Purchase_order $record) => $record->bpb()->count() === 0)
                        ->action(function (Purchase_order $record) {

                            // Soft delete semua BPB terkait PO
                            $bpb = Bpb::where('po_id', $record->id)->get();
                            foreach ($bpb as $b) {
                                $b->delete(); // soft delete
                            }

                            // Kosongkan purchase_order_id di semua Bppb_item / Bppb_ink / Bppb_software
                            Bppb_item::where('purchase_order_id', $record->id)->update(['purchase_order_id' => null]);
                            Bppb_ink::where('purchase_order_id', $record->id)->update(['purchase_order_id' => null]);
                            Bppb_software::where('purchase_order_id', $record->id)->update(['purchase_order_id' => null]);

                            // Soft delete PO
                            $record->delete();

                            Notification::make()
                                ->title('Purchase Order berhasil dihapus')
                                ->success()
                                ->send();
                        }),
                ]),
                Action::make('createBpb')
                    ->label('Create BPB')
                    ->color('success')
                    ->icon('heroicon-m-plus-circle')
                    ->requiresConfirmation()
                    ->visible(
                        fn(Purchase_order $record) =>
                        $record->bpb()->count() === 0
                            && in_array($record->bppb?->bppb_type_id, [1, 3])
                    )
                    ->action(function (Purchase_order $record) {
                        // Redirect ke create Bpb Page
                        return redirect()->route('filament.sis.resources.bpbs.create', [
                            'bppb_id' => $this->bppbId,
                            'po_id' => $record->id,
                        ]);
                    }),
            ]);
    }

    public function render(): View
    {
        return view('livewire.bppb-purchase-order-table');
    }
}
