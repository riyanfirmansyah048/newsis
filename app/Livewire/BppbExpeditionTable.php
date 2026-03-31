<?php

namespace App\Livewire;

use App\Models\Expedition;
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
use Livewire\Component;

class BppbExpeditionTable extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public int $bppbId;

    protected function getTableQuery()
    {
        return Expedition::query()
            ->withTrashed()
            ->where('bppb_id', $this->bppbId);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->columns([
                TextColumn::make('noExpedition')
                    ->label('No. Expedisi')
                    ->searchable()
                    ->sortable()
                    ->color(fn(Expedition $record) => $record->trashed() ? 'danger' : null)
                    ->copyable()
                    ->copyMessage('No. Expedisi berhasil disalin')
                    ->copyMessageDuration(1500),

                TextColumn::make('expeditor')
                    ->label('Pengirim')
                    ->searchable(),

                TextColumn::make('dateStart')
                    ->label('Tanggal Expedisi')
                    ->date('d F Y')
                    ->sortable(),

                TextColumn::make('datePrint')
                    ->label('Tanggal Print')
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
            ->recordActions([
                Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-m-printer')
                    ->color('info')
                    ->url(fn(Expedition $record) => route('expedition.print', ['id' => $record->id]))
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
            ]);
    }

    public function render(): View
    {
        return view('livewire.bppb-expedition-table');
    }
}
