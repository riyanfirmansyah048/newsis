<?php

namespace App\Filament\Widgets;

use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class ServiceMonthlyTrendChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected ?string $pollingInterval = null;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $selectedPicId = $this->filter;

        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->push([
                'label' => $date->format('M Y'),
                'year' => $date->year,
                'month' => $date->month,
            ]);
        }

        $labels = $months->pluck('label')->toArray();

        if ($selectedPicId && $selectedPicId !== 'all') {
            $data = [];
            foreach ($months as $m) {
                $count = Service::where('ic_id', $selectedPicId)
                    ->whereYear('created_at', $m['year'])
                    ->whereMonth('created_at', $m['month'])
                    ->count();
                $data[] = $count;
            }
            $picName = User::find($selectedPicId)?->name ?? 'Unknown';
            $datasets = [
                [
                    'label' => $picName,
                    'data' => $data,
                    'borderColor' => '#36b9cc',
                    'backgroundColor' => 'rgba(54, 185, 204, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ];
        } else {
            $pics = Service::whereNotNull('ic_id')
                ->select('ic_id')
                ->distinct()
                ->with('icUser:id,name')
                ->get();

            $colors = ['#36b9cc', '#e74a3b', '#f6c23e', '#4e73df', '#1cc88a', '#858796', '#5a5c69'];
            $datasets = [];
            $i = 0;
            foreach ($pics as $pic) {
                $data = [];
                foreach ($months as $m) {
                    $count = Service::where('ic_id', $pic->ic_id)
                        ->whereYear('created_at', $m['year'])
                        ->whereMonth('created_at', $m['month'])
                        ->count();
                    $data[] = $count;
                }
                $datasets[] = [
                    'label' => $pic->icUser?->name ?? 'Unknown',
                    'data' => $data,
                    'borderColor' => $colors[$i % count($colors)],
                    'backgroundColor' => 'rgba(0,0,0,0)',
                    'tension' => 0.3,
                ];
                $i++;
            }
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function getHeading(): string
    {
        return 'Tren Service per Bulan';
    }

    protected function getFilters(): ?array
    {
        $pics = Service::whereNotNull('ic_id')
            ->select('ic_id')
            ->distinct()
            ->with('icUser:id,name')
            ->get()
            ->pluck('icUser.name', 'ic_id')
            ->map(fn ($name) => $name ?? 'Unknown')
            ->toArray();

        return ['all' => 'Semua PIC'] + $pics;
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
