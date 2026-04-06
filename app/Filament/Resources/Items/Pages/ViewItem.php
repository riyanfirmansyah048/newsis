<?php

namespace App\Filament\Resources\Items\Pages;

use App\Models\Bppb_item;
use App\Filament\Resources\Items\ItemResource;
use Filament\Resources\Pages\ViewRecord;

class ViewItem extends ViewRecord
{
    protected static string $resource = ItemResource::class;

    protected string $view = 'filament.resources.items.pages.view-item';

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getViewData(): array
    {
        $record = $this->record->load(['category', 'brand']);

        $bppbHistories = Bppb_item::query()
            ->with(['bppb.user', 'bppb.status'])
            ->where('item_id', $record->id)
            ->get()
            ->groupBy('bppb_id')
            ->map(function ($rows) {
                $first = $rows->first();

                return [
                    'no_bppb' => $first->bppb?->noBppb ?? '-',
                    'pemohon' => $first->bppb?->user?->name ?? '-',
                    'tanggal' => $first->bppb?->created_at,
                    'status' => $first->bppb?->status?->name ?? '-',
                    'qty' => $rows->sum('qty'),
                ];
            })
            ->sortByDesc(fn ($row) => $row['tanggal'])
            ->values()
            ->all();

        return [
            'record' => $record,
            'bppbHistories' => $bppbHistories,
        ];
    }
}
