<?php

namespace App\Filament\Widgets;

use App\Models\Service;
use Filament\Widgets\ChartWidget;

class ServicePicStatusChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $pollingInterval = null;

    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $statuses = [
            4 => 'Barang di IT',
            5 => 'Proses Service',
            6 => 'Selesai (Di IT)',
            7 => 'Selesai (Diserahkan)',
            8 => 'Pending',
        ];

        $statusColors = [
            4 => '#28a745',
            5 => '#6c757d',
            6 => '#17a2b8',
            7 => '#28a745',
            8 => '#ffc107',
        ];

        $pics = Service::whereNotNull('ic_id')
            ->select('ic_id')
            ->distinct()
            ->with('icUser:id,name')
            ->get()
            ->sortBy('icUser.name');

        $labels = $pics->pluck('icUser.name', 'ic_id')
            ->map(fn ($name) => $name ?? 'Unknown')
            ->values()
            ->toArray();

        $counts = Service::whereNotNull('ic_id')
            ->whereIn('status_id', array_keys($statuses))
            ->selectRaw('ic_id, status_id, COUNT(*) as total')
            ->groupBy('ic_id', 'status_id')
            ->get()
            ->groupBy('ic_id');

        $datasets = [];
        foreach ($statuses as $statusId => $statusLabel) {
            $data = [];
            foreach ($pics as $pic) {
                $picCounts = $counts->get($pic->ic_id, collect());
                $data[] = $picCounts->firstWhere('status_id', $statusId)?->total ?? 0;
            }
            $datasets[] = [
                'label' => $statusLabel,
                'data' => $data,
                'backgroundColor' => $statusColors[$statusId],
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    public function getHeading(): string
    {
        return 'Status Service per PIC';
    }

    protected function getOptions(): ?array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'scales' => [
                'x' => [
                    'stacked' => true,
                ],
                'y' => [
                    'stacked' => true,
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}
