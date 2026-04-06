<?php

namespace App\Filament\Resources\Software\Pages;

use App\Models\Bppb_software;
use App\Filament\Resources\Software\SoftwareResource;
use Filament\Resources\Pages\ViewRecord;

class ViewSoftware extends ViewRecord
{
    protected static string $resource = SoftwareResource::class;

    protected string $view = 'filament.resources.software.pages.view-software';

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getViewData(): array
    {
        $record = $this->record->load(['category_software', 'brand_software']);

        $bppbHistories = Bppb_software::query()
            ->with(['bppb.user', 'bppb.status'])
            ->where('software_id', $record->id)
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
