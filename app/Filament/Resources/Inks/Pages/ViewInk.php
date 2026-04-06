<?php

namespace App\Filament\Resources\Inks\Pages;

use App\Models\Bppb_ink;
use App\Filament\Resources\Inks\InkResource;
use Filament\Resources\Pages\ViewRecord;

class ViewInk extends ViewRecord
{
    protected static string $resource = InkResource::class;

    protected string $view = 'filament.resources.inks.pages.view-ink';

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getViewData(): array
    {
        $record = $this->record->load(['category_ink', 'brand_ink']);

        $bppbHistories = Bppb_ink::query()
            ->with(['bppb.user', 'bppb.status'])
            ->where('ink_id', $record->id)
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
