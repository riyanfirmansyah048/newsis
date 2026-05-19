<?php

namespace App\Filament\Widgets;

use App\Models\Service;
use Filament\Widgets\ChartWidget;

class ServicePerPicChart extends ChartWidget
{
    protected static ?int $sort = 1;

    protected ?string $pollingInterval = null;

    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $services = Service::query()
            ->whereNotNull('ic_id')
            ->selectRaw('ic_id, COUNT(*) as total')
            ->groupBy('ic_id')
            ->with('icUser:id,name')
            ->get()
            ->sortByDesc('total');

        $labels = $services->pluck('icUser.name', 'ic_id')
            ->map(fn ($name) => $name ?? 'Unknown')
            ->values()
            ->toArray();

        $data = $services->pluck('total')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Service',
                    'data' => $data,
                    'backgroundColor' => '#36b9cc',
                    'borderColor' => '#2a9daf',
                    'borderWidth' => 1,
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    public function getHeading(): string
    {
        return 'Jumlah Service per PIC';
    }

    protected function getOptions(): ?array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}
