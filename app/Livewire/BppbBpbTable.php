<?php

namespace App\Livewire;

use App\Models\Bpb;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Textarea;
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
            ->withTrashed()
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
                    ->color(fn(Bpb $record) => $record->trashed() ? 'danger' : null)
                    ->searchable(),

                TextColumn::make('purchase_order.noPo')
                    ->label('No. Purchase Order')
                    ->searchable(),

                TextColumn::make('dateBpb')
                    ->label('Tanggal BPB')
                    ->date('d F Y'),

                TextColumn::make('deleted_at')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state ? 'Cancelled' : 'Active')
                    ->color(fn($state) => $state ? 'danger' : 'success'),
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
                    )
                    ->visible(fn(Bpb $record) => ! $record->trashed()),
                Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->visible(fn(Bpb $record) => auth()->user()->hasRole('admin') && ! $record->trashed())
                    ->schema([
                        Textarea::make('reason')
                            ->label('Alasan Cancel')
                            ->required()
                            ->rows(4)
                            ->placeholder('Masukkan alasan kenapa BPB di-cancel'),
                    ])
                    ->action(function (array $data, Bpb $record) {
                        $reason = trim($data['reason']);

                        activity()
                            ->performedOn($record)
                            ->causedBy(auth()->user())
                            ->withProperties([
                                'reason' => $reason,
                                'noBpb' => $record->noBpb,
                            ])
                            ->log("cancelled: {$reason}");

                        activity()->withoutLogs(function () use ($record) {
                            $record->delete();
                        });
                    }),
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
