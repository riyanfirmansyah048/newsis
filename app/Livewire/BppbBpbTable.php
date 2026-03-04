<?php

namespace App\Livewire;

use App\Models\Bpb;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class BppbBpbTable extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public int $bppbId;

    protected function getTableQuery(): Builder
    {
        return Bpb::query()
            ->whereHas('purchase_order', function ($q) {
                $q->where('bppb_id', $this->bppbId);
            });
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->columns([
                TextColumn::make('noBpb')
                    ->label('No. BPB')
                    ->searchable(),

                TextColumn::make('purchase_order.noPo')
                    ->label('No. Purchase Order')
                    ->searchable(),

                TextColumn::make('dateBpb')
                    ->label('Tanggal BPB')
                    ->date('d F Y'),
            ])
            ->defaultSort('dateBpb', 'desc')
            ->filters([
                // ...
            ])
            ->recordActions([
                Action::make('print')
                    ->label('Print BPB')
                    ->icon('heroicon-m-printer')
                    ->color('info')
                    ->url(
                        fn($record) =>
                        route('bpb.print', ['id' => $record->id])
                    ),
                DeleteAction::make()
                    ->label('Delete')
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->visible(fn() => auth()->user()->hasRole('admin')),
            ])
            ->toolbarActions([
                // ...
            ]);
    }

    public function render(): View
    {
        return view('livewire.bppb-bpb-table');
    }
}
