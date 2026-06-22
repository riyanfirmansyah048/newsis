<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Models\Bppb_software;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class BppbSoftwareTable extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public int $bppbId;
    public int $statusId;

    protected function getTableQuery(): Builder
    {
        return Bppb_software::query()
            ->with(['bppb', 'software', 'user'])
            ->where('bppb_id', $this->bppbId);
    }

    protected function canInlineEdit(): bool
    {
        return Auth::user()?->hasRole('admin') && ! in_array($this->statusId, [1, 2, 3]);
    }

    protected function normalizeInlineValue(?string $value): string
    {
        $value = trim((string) $value);

        return $value !== '' ? $value : '-';
    }

    protected function syncToSource(Bppb_software $record, string $field, ?string $value): void
    {
        if (! $record->source_bppb_software_id) {
            return;
        }

        $normalized = $this->normalizeInlineValue($value);

        Bppb_software::withoutEvents(function () use ($record, $field, $normalized) {
            Bppb_software::where('id', $record->source_bppb_software_id)
                ->update([$field => $normalized]);
        });
    }

    protected function getLinkedBppbNumbers(Bppb_software $record): string
    {
        $currentNoBppb = $record->bppb?->noBppb;

        if (! $currentNoBppb) {
            return '-';
        }

        $numbers = Bppb_software::query()
            ->with('bppb')
            ->where('software_id', $record->software_id)
            ->where('noBppbPemohon', $currentNoBppb)
            ->where('bppb_id', '!=', $record->bppb_id)
            ->get()
            ->pluck('bppb.noBppb')
            ->filter()
            ->unique()
            ->values();

        if ($numbers->isNotEmpty()) {
            return $numbers->implode(', ');
        }

        if (! empty($record->noBppbPemohon)) {
            return $record->noBppbPemohon;
        }

        return '-';
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->columns([
                TextColumn::make('software.name')
                    ->label('Type Software'),
                TextColumn::make('user.name')
                    ->label('Pemohon IT')
                    ->default('-'),
                TextInputColumn::make('userPemohon')
                    ->label('User Pemohon')
                    ->default('-')
                    ->rules(['max:255'])
                    ->disabled(fn () => ! $this->canInlineEdit())
                    ->updateStateUsing(function (Bppb_software $record, ?string $state) {
                        $record->update(['userPemohon' => $this->normalizeInlineValue($state)]);
                        $this->syncToSource($record, 'userPemohon', $state);
                    }),
                TextInputColumn::make('departementPemohon')
                    ->label('Departemen')
                    ->default('-')
                    ->rules(['max:255'])
                    ->disabled(fn () => ! $this->canInlineEdit())
                    ->updateStateUsing(function (Bppb_software $record, ?string $state) {
                        $record->update(['departementPemohon' => $this->normalizeInlineValue($state)]);
                        $this->syncToSource($record, 'departementPemohon', $state);
                    }),
                TextInputColumn::make('lokasiPemohon')
                    ->label('Lokasi')
                    ->default('-')
                    ->rules(['max:255'])
                    ->disabled(fn () => ! $this->canInlineEdit())
                    ->updateStateUsing(function (Bppb_software $record, ?string $state) {
                        $record->update(['lokasiPemohon' => $this->normalizeInlineValue($state)]);
                        $this->syncToSource($record, 'lokasiPemohon', $state);
                    }),
                TextColumn::make('noBppbPemohon')
                    ->label('No. BPPB Pemohon')
                    ->default('-')
                    ->copyable(),
                TextColumn::make('linked_bppb')
                    ->label('Terhubung ke BPPB')
                    ->getStateUsing(fn(Bppb_software $record) => $this->getLinkedBppbNumbers($record))
                    ->wrap(),
                TextInputColumn::make('serialNumber')
                    ->label('Serial Number')
                    ->default('-')
                    ->rules(['max:255'])
                    ->visible(fn() => Auth::user()?->hasRole('admin'))
                    ->disabled(fn () => ! $this->canInlineEdit())
                    ->updateStateUsing(function (Bppb_software $record, ?string $state) {
                        $record->update(['serialNumber' => $this->normalizeInlineValue($state)]);
                        $this->syncToSource($record, 'serialNumber', $state);
                    }),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label('Edit')
                    ->color('success')
                    ->icon('heroicon-m-pencil-square')
                    ->visible(fn() => $this->canInlineEdit())
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        return redirect()->route('filament.sis.resources.bppb-software.edit', [
                            'bppb_id' => $this->bppbId,
                            'record' => $record->id,
                        ]);
                    }),
            ])
            ->defaultSort('id')
            ->toolbarActions([]);
    }

    public function render(): View
    {
        return view('livewire.bppb-software-table');
    }
}