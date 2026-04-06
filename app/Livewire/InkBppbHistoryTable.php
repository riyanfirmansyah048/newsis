<?php

namespace App\Livewire;

use App\Filament\Resources\Bppbs\BppbResource;
use App\Models\Bppb_ink;
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

class InkBppbHistoryTable extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public int $inkId;

    protected function getTableQuery(): Builder
    {
        $baseQuery = Bppb_ink::query()
            ->selectRaw('MIN(bppb_inks.id) as id, bppb_inks.bppb_id, bppbs.noBppb as no_bppb, users.name as pemohon, bppbs.created_at as tanggal, bppb_statuses.name as status, SUM(bppb_inks.qty) as qty')
            ->leftJoin('bppbs', 'bppbs.id', '=', 'bppb_inks.bppb_id')
            ->leftJoin('users', 'users.id', '=', 'bppbs.user_id')
            ->leftJoin('bppb_statuses', 'bppb_statuses.id', '=', 'bppbs.status_id')
            ->where('bppb_inks.ink_id', $this->inkId)
            ->groupBy('bppb_inks.bppb_id', 'bppbs.noBppb', 'users.name', 'bppbs.created_at', 'bppb_statuses.name');

        return Bppb_ink::query()->getModel()->newQueryWithoutScopes()
            ->fromSub($baseQuery, 'ink_bppb_histories')
            ->select('ink_bppb_histories.*');
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
        return view('livewire.ink-bppb-history-table');
    }
}
