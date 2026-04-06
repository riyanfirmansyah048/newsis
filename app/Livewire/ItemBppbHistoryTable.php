<?php

namespace App\Livewire;

use App\Filament\Resources\Bppbs\BppbResource;
use App\Models\Bppb_item;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ItemBppbHistoryTable extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public int $itemId;

    protected function getTableQuery(): Builder
    {
        $baseQuery = Bppb_item::query()
            ->selectRaw('MIN(bppb_items.id) as id, bppb_items.bppb_id, bppbs.noBppb as no_bppb, users.name as pemohon, bppbs.created_at as tanggal, bppb_statuses.name as status, SUM(bppb_items.qty) as qty')
            ->leftJoin('bppbs', 'bppbs.id', '=', 'bppb_items.bppb_id')
            ->leftJoin('users', 'users.id', '=', 'bppbs.user_id')
            ->leftJoin('bppb_statuses', 'bppb_statuses.id', '=', 'bppbs.status_id')
            ->where('bppb_items.item_id', $this->itemId)
            ->groupBy('bppb_items.bppb_id', 'bppbs.noBppb', 'users.name', 'bppbs.created_at', 'bppb_statuses.name');

        return Bppb_item::query()->getModel()->newQueryWithoutScopes()
            ->fromSub($baseQuery, 'item_bppb_histories')
            ->select('item_bppb_histories.*');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->defaultKeySort(false)
            ->columns([
                TextColumn::make('no_bppb')
                    ->label('No. BPPB')
                    ->url(fn ($record) => BppbResource::getUrl('edit', ['record' => $record->bppb_id]))
                    ->openUrlInNewTab()
                    ->copyable()
                    ->searchable(),
                TextColumn::make('pemohon')
                    ->label('Pemohon')
                    ->searchable(),
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d F Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ((string) $state) {
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        default => 'warning',
                    }),
                TextColumn::make('qty')
                    ->label('Qty')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('tanggal', 'desc');
    }

    public function render(): View
    {
        return view('livewire.item-bppb-history-table');
    }
}
