<?php

namespace App\Filament\Resources\Bpbs\Pages;

use App\Models\Bpb;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Bpbs\BpbResource;

class EditBpb extends EditRecord
{
    protected static string $resource = BpbResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
            Action::make('Print')
                ->url(fn(Bpb $record) => route('bpb.print', $record->id))
                // ->openUrlInNewTab()
                ->icon('heroicon-o-printer')
                ->color('success'),
        ];
    }
}
