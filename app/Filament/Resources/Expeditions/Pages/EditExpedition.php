<?php

namespace App\Filament\Resources\Expeditions\Pages;

use App\Filament\Resources\Expeditions\ExpeditionResource;
use App\Models\Expedition;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditExpedition extends EditRecord
{
    protected static string $resource = ExpeditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Print')
                ->url(fn(Expedition $record) => route('expedition.print', $record->id))
                ->openUrlInNewTab()
                ->icon('heroicon-o-printer')
                ->color('success'),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->record;

        $details = \App\Models\ExpeditionDetail::where('expedition_id', $record->id)->get();

        $data['include_items'] = $details
            ->map(
                fn($detail) =>
                $detail->type_id . '|' . $detail->product_form_id
            )
            ->toArray();

        return $data;
    }
}
