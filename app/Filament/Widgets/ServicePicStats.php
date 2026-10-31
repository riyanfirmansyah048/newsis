<?php

namespace App\Filament\Widgets;

use App\Models\Service;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ServicePicStats extends StatsOverviewWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $user = Auth::user();
        if (! $user?->can('access-all-service')) {
            return [];
        }

        $totalPic = Service::whereNotNull('ic_id')
            ->distinct('ic_id')
            ->count('ic_id');

        $totalHandled = Service::whereNotNull('ic_id')->count();

        $activeServices = Service::whereNotNull('ic_id')
            ->whereIn('status_id', [4, 5])
            ->count();

        $avgDays = Service::whereNotNull('ic_id')
            ->whereNotNull('received_date')
            ->whereNotNull('finish_date')
            ->selectRaw('AVG(DATEDIFF(finish_date, received_date)) as avg_days')
            ->value('avg_days');

        $avgDaysFormatted = $avgDays ? number_format((float) $avgDays, 1).' hari' : '-';

        return [
            Stat::make('Total PIC', number_format($totalPic))
                ->description('Jumlah PIC yang pernah ditugaskan')
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Service Ditangani', number_format($totalHandled))
                ->description('Total service yang memiliki PIC')
                ->icon('heroicon-o-wrench-screwdriver')
                ->color('info'),

            Stat::make('Sedang Dikerjakan', number_format($activeServices))
                ->description('Status Barang di IT / Proses Service')
                ->icon('heroicon-o-arrow-path')
                ->color('warning'),

            Stat::make('Rata-rata Penyelesaian', $avgDaysFormatted)
                ->description('Dari received_date ke finish_date')
                ->icon('heroicon-o-clock')
                ->color('success'),
        ];
    }

    protected function getHeading(): ?string
    {
        return 'Statistik PIC';
    }

    protected function getDescription(): ?string
    {
        return 'Ringkasan Service / Memo IT';
    }

    protected function getColumns(): int|array|null
    {
        return 4;
    }
}
