<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Models\Bppb_software;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
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

    // Query langsung ke software terkait BPPB
    protected function getTableQuery(): Builder
    {
        return Bppb_software::query()
            ->where('bppb_id', $this->bppbId);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->columns([
                TextColumn::make('software.name')
                    ->label('Type Software'),
                TextColumn::make('user.name')
                    ->label('Pemohon IT'),
                TextColumn::make('userPemohon')
                    ->label('User Pemohon'),
                TextColumn::make('departementPemohon')
                    ->label('Departemen'),
                TextColumn::make('lokasiPemohon')
                    ->label('Lokasi'),
                TextColumn::make('serialNumber')
                    ->label('Serial Number'),
            ])
            ->filters([
                // ...
            ])
            ->recordActions([
                Action::make('edit')
                    ->label('Edit')
                    ->color('success')
                    ->icon('heroicon-m-pencil-square')
                    ->visible(
                        fn($record) =>
                        Auth::user()?->hasRole('admin')
                            && !in_array($this->statusId, [1, 2, 3])
                    )
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        return redirect()->route('filament.sis.resources.bppb-software.edit', [
                            'bppb_id' => $this->bppbId,
                            'record' => $record->id,
                        ]);
                    }),
            ])
            ->defaultSort('id')
            ->toolbarActions([
                // ...
            ]);
    }

    public function render(): View
    {
        return view('livewire.bppb-software-table');
    }
}
